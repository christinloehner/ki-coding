<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ArticleRevision;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request): View
    {
        $query = Article::with(['user', 'category', 'tags'])
            ->published();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by tag
        if ($request->has('tag') && $request->tag) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'latest':
            default:
                $query->latest('published_at');
                break;
        }

        $articles = $query->paginate(12);

        $categories = Category::active()->root()->get();
        $popularTags = Tag::popular()->take(20)->get();

        return view('wiki.articles.index', compact('articles', 'categories', 'popularTags'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        Gate::authorize('create', Article::class);

        $categories = Category::active()->get();
        $tags = Tag::all();

        return view('wiki.articles.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Article::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'status' => 'required|in:draft,pending_review,published',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $createdArticle = DB::transaction(function () use ($validated) {
            $article = Article::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
                'category_id' => $validated['category_id'],
                'user_id' => Auth::id(),
                'status' => $validated['status'],
                'is_featured' => $validated['is_featured'] ?? false,
                'meta_title' => $validated['meta_title'],
                'meta_description' => $validated['meta_description'],
                'meta_keywords' => $validated['meta_keywords'],
                'published_at' => $validated['status'] === 'published' ? now() : null,
            ]);

            // Attach tags
            if (isset($validated['tags'])) {
                $article->tags()->attach($validated['tags']);
            }

            // Create initial revision
            $article->createRevision(
                Auth::user(),
                'Initial article creation'
            );

            return $article;
        });

        return redirect()->route('wiki.articles.show', $createdArticle->slug)
            ->with('success', 'Artikel wurde erfolgreich erstellt.');
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): View
    {
        try {
            // Check if user can view this article
            if ($article->status !== 'published') {
                // Allow author and users with edit permissions to view drafts
                if (!auth()->check() || 
                    (auth()->id() !== $article->user_id && !auth()->user()->can('edit articles'))) {
                    abort(404);
                }
            }

            $article->load(['user', 'category', 'tags']);
            $article->increment('views_count');

            $relatedArticles = Article::where('status', 'published')
                ->where('id', '!=', $article->id)
                ->where('category_id', $article->category_id)
                ->with(['user', 'category'])
                ->take(3)
                ->get();

            $revisions = collect(); // Erstmal leer lassen

            return view('wiki.articles.show', compact('article', 'relatedArticles', 'revisions'));
        } catch (\Exception $e) {
            Log::error('Article Show Error: ' . $e->getMessage());
            abort(500, 'Artikel konnte nicht geladen werden');
        }
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): View
    {
        Gate::authorize('update', $article);

        $categories = Category::active()->get();
        $tags = Tag::all();
        $selectedTags = $article->tags->pluck('id')->toArray();

        return view('wiki.articles.edit', compact('article', 'categories', 'tags', 'selectedTags'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        Gate::authorize('update', $article);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'status' => 'required|in:draft,pending_review,published',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'revision_summary' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $article) {
            $oldStatus = $article->status;

            $article->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'is_featured' => $validated['is_featured'] ?? false,
                'meta_title' => $validated['meta_title'],
                'meta_description' => $validated['meta_description'],
                'meta_keywords' => $validated['meta_keywords'],
                'published_at' => $validated['status'] === 'published' && $oldStatus !== 'published' ? now() : $article->published_at,
            ]);

            // Sync tags
            if (isset($validated['tags'])) {
                $article->tags()->sync($validated['tags']);
            } else {
                $article->tags()->detach();
            }

            // Create revision
            $article->createRevision(
                Auth::user(),
                $validated['revision_summary'] ?? 'Article updated'
            );
        });

        return redirect()->route('wiki.articles.show', $article->slug)
            ->with('success', 'Artikel wurde erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        Gate::authorize('delete', $article);

        $article->delete();

        return redirect()->route('wiki.articles.index')
            ->with('success', 'Artikel wurde erfolgreich gelöscht.');
    }

    /**
     * Display article revision history.
     */
    public function history(Article $article): View
    {
        Gate::authorize('view', $article);

        $revisions = $article->revisions()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('wiki.articles.history', compact('article', 'revisions'));
    }

    /**
     * Display specific revision.
     */
    public function revision(Article $article, ArticleRevision $revision): View
    {
        Gate::authorize('view', $article);

        if ($revision->article_id !== $article->id) {
            abort(404);
        }

        $revision->load('user');

        return view('wiki.articles.revision', compact('article', 'revision'));
    }

    /**
     * Restore article to specific revision.
     */
    public function restore(Article $article, ArticleRevision $revision): RedirectResponse
    {
        Gate::authorize('update', $article);

        if ($revision->article_id !== $article->id) {
            abort(404);
        }

        DB::transaction(function () use ($article, $revision) {
            $article->update([
                'title' => $revision->title,
                'content' => $revision->content,
                'excerpt' => $revision->excerpt,
            ]);

            $article->createRevision(
                Auth::user(),
                "Restored to revision #{$revision->version_number}"
            );
        });

        return redirect()->route('wiki.articles.show', $article->slug)
            ->with('success', 'Artikel wurde erfolgreich zur Version #' . $revision->version . ' wiederhergestellt.');
    }

    /**
     * Toggle featured status of article.
     */
    public function toggleFeatured(Article $article): RedirectResponse
    {
        Gate::authorize('moderate', $article);

        $article->update([
            'is_featured' => !$article->is_featured
        ]);

        $status = $article->is_featured ? 'als hervorgehoben markiert' : 'als hervorgehoben entfernt';

        return back()->with('success', "Artikel wurde {$status}.");
    }

    /**
     * Change article status.
     */
    public function changeStatus(Request $request, Article $article): RedirectResponse
    {
        Gate::authorize('moderate', $article);

        $validated = $request->validate([
            'status' => 'required|in:draft,pending_review,published,archived',
            'reason' => 'nullable|string|max:500',
        ]);

        $oldStatus = $article->status;

        $article->update([
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' && $oldStatus !== 'published' ? now() : $article->published_at,
        ]);

        $article->createRevision(
            Auth::user(),
            $validated['reason'] ?? "Status changed from {$oldStatus} to {$validated['status']}"
        );

        return back()->with('success', 'Artikel-Status wurde erfolgreich geändert.');
    }

    /**
     * Request deletion of an article (normal users)
     */
    public function requestDeletion(Request $request, Article $article): RedirectResponse
    {
        // Check if user can delete their own article or if admin/moderator
        if (auth()->user()->cannot('delete', $article)) {
            abort(403);
        }

        // Prevent double requests
        if ($article->deletion_requested_at) {
            return back()->withErrors(['deletion' => 'Deletion already requested for this article.']);
        }

        $request->validate([
            'deletion_reason' => 'required|string|max:500'
        ]);

        $article->update([
            'deletion_requested_at' => now(),
            'deletion_reason' => $request->deletion_reason,
            'deletion_requested_by' => auth()->id(),
        ]);

        return back()->with('success', 'Deletion request has been submitted for review.');
    }

    /**
     * Cancel deletion request (author only)
     */
    public function cancelDeletionRequest(Article $article): RedirectResponse
    {
        // Only author can cancel
        if ($article->deletion_requested_by !== auth()->id()) {
            abort(403);
        }

        $article->update([
            'deletion_requested_at' => null,
            'deletion_reason' => null,
            'deletion_requested_by' => null,
        ]);

        return back()->with('success', 'Deletion request has been cancelled.');
    }

    /**
     * Approve deletion and delete article (admin/moderator only)
     */
    public function approveDeletion(Article $article): RedirectResponse
    {
        Gate::authorize('delete all articles');

        if (!$article->deletion_requested_at) {
            return back()->withErrors(['deletion' => 'No deletion request found for this article.']);
        }

        $title = $article->title;
        $article->delete();

        return redirect()->route('wiki.articles.index')
            ->with('success', "Article '{$title}' has been deleted.");
    }

    /**
     * Deny deletion request (admin/moderator only)
     */
    public function denyDeletion(Article $article): RedirectResponse
    {
        Gate::authorize('delete all articles');

        if (!$article->deletion_requested_at) {
            return back()->withErrors(['deletion' => 'No deletion request found for this article.']);
        }

        $article->update([
            'deletion_requested_at' => null,
            'deletion_reason' => null,
            'deletion_requested_by' => null,
        ]);

        return back()->with('success', 'Deletion request has been denied.');
    }
}
