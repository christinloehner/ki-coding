<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Article;
use App\Models\Tag;
use App\Models\Category;
use App\Models\UserActivity;
use App\Events\ArticlePublished;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Class ArticleController
 * 
 * RESTful API Controller für Artikel-Management
 * Authentifizierung: Bearer Token (Laravel Sanctum)
 * Berechtigung: 'create articles' Permission erforderlich
 * 
 * @package App\Http\Controllers\Api\V1
 */
class ArticleController extends Controller
{
    use ApiResponseTrait;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Authorization Check
        if (!Auth::user()->can('create articles')) {
            return $this->unauthorizedResponse('Du hast keine Berechtigung, Artikel zu erstellen.');
        }

        try {
            // Request Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'excerpt' => 'nullable|string|max:500',
                'category_slug' => 'required|string|exists:categories,slug',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'status' => 'required|in:draft,pending_review,published',
                'is_featured' => 'boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:500',
            ]);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors(), 'Die übermittelten Daten sind ungültig.');
        }

        try {
            $article = DB::transaction(function () use ($validated, $request) {
                // Find category
                $category = Category::where('slug', $validated['category_slug'])->first();
                
                if (!$category) {
                    throw new \Exception('Kategorie nicht gefunden.');
                }

                // Create article
                $article = Article::create([
                    'title' => $validated['title'],
                    'content' => $validated['content'],
                    'excerpt' => $validated['excerpt'] ?? null,
                    'category_id' => $category->id,
                    'user_id' => Auth::id(),
                    'status' => $validated['status'],
                    'is_featured' => $validated['is_featured'] ?? false,
                    'meta_title' => $validated['meta_title'] ?? null,
                    'meta_description' => $validated['meta_description'] ?? null,
                    'meta_keywords' => $validated['meta_keywords'] ?? null,
                    'published_at' => ($validated['status'] === 'published') ? now() : null,
                ]);

                // Trigger publication event
                if ($validated['status'] === 'published') {
                    event(new ArticlePublished($article));
                }

                // Handle tags
                if (!empty($validated['tags'])) {
                    $tagIds = [];
                    foreach ($validated['tags'] as $tagName) {
                        $tag = Tag::firstOrCreate(
                            ['name' => strtolower($tagName)],
                            ['slug' => \Illuminate\Support\Str::slug($tagName)]
                        );
                        $tagIds[] = $tag->id;
                    }
                    $article->tags()->sync($tagIds);
                }

                // Load relationships for response
                $article->load(['category', 'tags', 'user:id,name']);
                
                return $article;
            });

            // Log activity
            UserActivity::log(
                Auth::id(),
                'article_created_api',
                "Hat den Artikel \"{$article->title}\" über die API erstellt",
                $article
            );

            return $this->createdResponse($article, 'Artikel wurde erfolgreich erstellt.');

        } catch (\Exception $e) {
            return $this->errorResponse('Fehler beim Erstellen des Artikels: ' . $e->getMessage(), 500);
        }
    }
}
