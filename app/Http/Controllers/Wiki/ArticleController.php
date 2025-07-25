<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\ArticleRevision;
use App\Models\UserActivity;
use App\Events\ArticleLiked;
use App\Events\ArticlePublished;
use App\Events\ArticleUnliked;
use App\Events\ArticleVoted;
use App\Services\DiffService;
use App\Notifications\ArticleLikedNotification;
use App\Notifications\ArticleBookmarkedNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
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
            ->withCount(['comments'])
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

        // Kategorien hierarchisch laden: Hauptkategorien mit ihren Unterkategorien
        $categories = Category::active()
            ->whereNull('parent_id') // Nur Hauptkategorien
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
            
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
            'excerpt' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,pending_review,published',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ], [
            'excerpt.max' => 'Die Kurzbeschreibung darf maximal 1000 Zeichen lang sein.',
            'title.required' => 'Der Titel ist erforderlich.',
            'title.max' => 'Der Titel darf maximal 255 Zeichen lang sein.',
            'content.required' => 'Der Inhalt ist erforderlich.',
            'category_id.required' => 'Bitte wähle eine Kategorie aus.',
            'category_id.exists' => 'Die ausgewählte Kategorie existiert nicht.',
            'status.required' => 'Bitte wähle einen Status aus.',
            'status.in' => 'Der gewählte Status ist ungültig.',
            'meta_title.max' => 'Der Meta-Titel darf maximal 255 Zeichen lang sein.',
            'meta_description.max' => 'Die Meta-Beschreibung darf maximal 500 Zeichen lang sein.',
            'meta_keywords.max' => 'Die Meta-Keywords dürfen maximal 500 Zeichen lang sein.',
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
            
            // Fire reputation event if article is published
            if ($validated['status'] === 'published') {
                event(new ArticlePublished($article));
            }

            // Process and attach tags
            if (!empty($validated['tags'])) {
                $tagNames = array_filter(array_map('trim', explode(',', $validated['tags'])));
                $tagIds = [];
                
                foreach ($tagNames as $tagName) {
                    // Find or create tag
                    $tag = Tag::firstOrCreate(
                        ['name' => strtolower($tagName)],
                        ['description' => '', 'color' => '#3B82F6']
                    );
                    $tagIds[] = $tag->id;
                }
                
                $article->tags()->attach($tagIds);
            }

            // Create initial revision
            $article->createRevision(
                Auth::user(),
                'Initial article creation'
            );

            return $article;
        });

        // Log activity
        UserActivity::log(
            Auth::id(),
            'article_created',
            "Hat den Artikel \"{$createdArticle->title}\" erstellt",
            $createdArticle
        );

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

            // Lade die letzten 5 Revisionen für eventuelle Verwendung
            $revisions = $article->revisions()->with('user')->latest()->take(5)->get();

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

        // Kategorien hierarchisch laden: Hauptkategorien mit ihren Unterkategorien
        $categories = Category::active()
            ->whereNull('parent_id') // Nur Hauptkategorien
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
            
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
            'excerpt' => 'nullable|string|max:1000',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,pending_review,published',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'revision_summary' => 'nullable|string|max:255',
        ], [
            'excerpt.max' => 'Die Kurzbeschreibung darf maximal 1000 Zeichen lang sein.',
            'title.required' => 'Der Titel ist erforderlich.',
            'title.max' => 'Der Titel darf maximal 255 Zeichen lang sein.',
            'content.required' => 'Der Inhalt ist erforderlich.',
            'category_id.required' => 'Bitte wähle eine Kategorie aus.',
            'category_id.exists' => 'Die ausgewählte Kategorie existiert nicht.',
            'status.required' => 'Bitte wähle einen Status aus.',
            'status.in' => 'Der gewählte Status ist ungültig.',
            'meta_title.max' => 'Der Meta-Titel darf maximal 255 Zeichen lang sein.',
            'meta_description.max' => 'Die Meta-Beschreibung darf maximal 500 Zeichen lang sein.',
            'meta_keywords.max' => 'Die Meta-Keywords dürfen maximal 500 Zeichen lang sein.',
            'revision_summary.max' => 'Der Änderungshinweis darf maximal 255 Zeichen lang sein.',
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
            // Process and sync tags
            if (!empty($validated['tags'])) {
                $tagNames = array_filter(array_map('trim', explode(',', $validated['tags'])));
                $tagIds = [];
                
                foreach ($tagNames as $tagName) {
                    // Find or create tag
                    $tag = Tag::firstOrCreate(
                        ['name' => strtolower($tagName)],
                        ['description' => '', 'color' => '#3B82F6']
                    );
                    $tagIds[] = $tag->id;
                }
                
                $article->tags()->sync($tagIds);
            } else {
                $article->tags()->detach();
            }

            // Create revision
            $article->createRevision(
                Auth::user(),
                $validated['revision_summary'] ?? 'Article updated'
            );
        });

        // Log activity
        UserActivity::log(
            Auth::id(),
            'article_updated',
            "Hat den Artikel \"{$article->title}\" bearbeitet",
            $article
        );

        return redirect()->route('wiki.articles.show', $article->slug)
            ->with('success', 'Artikel wurde erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        Gate::authorize('delete', $article);

        // Log activity before deletion
        UserActivity::log(
            Auth::id(),
            'article_deleted',
            "Hat den Artikel \"{$article->title}\" gelöscht",
            null,
            ['article_title' => $article->title]
        );

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
        
        // Fire reputation event if article gets published for first time
        if ($validated['status'] === 'published' && $oldStatus !== 'published') {
            event(new ArticlePublished($article));
        }

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

    /**
     * Toggle like for an article
     */
    public function toggleLike(Article $article): JsonResponse
    {
        $userId = Auth::id();

        // Check if user has already liked this article
        $existingLike = DB::table('article_likes')
            ->where('article_id', $article->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            // Remove like
            DB::table('article_likes')
                ->where('article_id', $article->id)
                ->where('user_id', $userId)
                ->delete();

            $article->decrement('likes_count');
            $liked = false;
            
            // Fire reputation event for unlike
            event(new ArticleUnliked($article, Auth::user()));
        } else {
            // Add like
            DB::table('article_likes')->insert([
                'article_id' => $article->id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $article->increment('likes_count');
            $liked = true;
            
            // Fire reputation event
            event(new ArticleLiked($article, Auth::user()));
            
            // Send notification to article author (not to self)
            if ($article->user_id !== $userId) {
                try {
                    $article->user->notify(new ArticleLikedNotification($article, Auth::user()));
                    
                } catch (\Exception $e) {
                    \Log::error('Failed to send article liked notification', [
                        'error' => $e->getMessage(),
                        'article_id' => $article->id,
                        'author_id' => $article->user_id,
                        'liker_id' => $userId
                    ]);
                }
            }
            
            // Log activity
            UserActivity::log(
                Auth::id(),
                'article_liked',
                "Hat den Artikel \"{$article->title}\" geliked",
                $article
            );
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $article->likes_count
        ]);
    }

    /**
     * Toggle bookmark for an article
     */
    public function toggleBookmark(Article $article): JsonResponse
    {
        $userId = Auth::id();

        // Check if user has already bookmarked this article
        $existingBookmark = DB::table('article_bookmarks')
            ->where('article_id', $article->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingBookmark) {
            // Remove bookmark
            DB::table('article_bookmarks')
                ->where('article_id', $article->id)
                ->where('user_id', $userId)
                ->delete();

            $bookmarked = false;
            
            // Log activity
            UserActivity::log(
                Auth::id(),
                'article_bookmark_removed',
                "Hat das Lesezeichen für \"{$article->title}\" entfernt",
                $article
            );
        } else {
            // Add bookmark
            DB::table('article_bookmarks')->insert([
                'article_id' => $article->id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $bookmarked = true;
            
            // Send notification to article author (not to self)
            if ($article->user_id !== $userId) {
                try {
                    $article->user->notify(new ArticleBookmarkedNotification($article, Auth::user()));
                    
                } catch (\Exception $e) {
                    \Log::error('Failed to send article bookmarked notification', [
                        'error' => $e->getMessage(),
                        'article_id' => $article->id,
                        'author_id' => $article->user_id,
                        'bookmarker_id' => $userId
                    ]);
                }
            }
            
            // Log activity
            UserActivity::log(
                Auth::id(),
                'article_bookmarked',
                "Hat den Artikel \"{$article->title}\" als Lesezeichen gespeichert",
                $article
            );
        }

        return response()->json([
            'success' => true,
            'bookmarked' => $bookmarked
        ]);
    }

    /**
     * Vote on article helpfulness
     */
    public function vote(Request $request, Article $article): JsonResponse
    {
        $validated = $request->validate([
            'helpful' => 'required|boolean',
        ]);

        $userId = Auth::id();
        $isHelpful = $validated['helpful'];

        // Check if user has already voted
        $existingVote = DB::table('article_votes')
            ->where('article_id', $article->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingVote) {
            // Update existing vote
            if (($existingVote->is_helpful && $isHelpful) || (!$existingVote->is_helpful && !$isHelpful)) {
                // Same vote - remove it
                DB::table('article_votes')
                    ->where('article_id', $article->id)
                    ->where('user_id', $userId)
                    ->delete();
                
                // Update counters
                if ($isHelpful) {
                    $article->decrement('helpful_votes');
                } else {
                    $article->decrement('not_helpful_votes');
                }

                return response()->json([
                    'success' => true,
                    'vote' => null,
                    'message' => 'Vote removed'
                ]);
            } else {
                // Different vote - update it
                DB::table('article_votes')
                    ->where('article_id', $article->id)
                    ->where('user_id', $userId)
                    ->update([
                        'is_helpful' => $isHelpful,
                        'updated_at' => now()
                    ]);

                // Update counters
                if ($isHelpful) {
                    $article->increment('helpful_votes');
                    $article->decrement('not_helpful_votes');
                } else {
                    $article->increment('not_helpful_votes');
                    $article->decrement('helpful_votes');
                }

                return response()->json([
                    'success' => true,
                    'vote' => $isHelpful ? 'helpful' : 'not_helpful',
                    'message' => 'Vote updated'
                ]);
            }
        } else {
            // Create new vote
            DB::table('article_votes')->insert([
                'article_id' => $article->id,
                'user_id' => $userId,
                'is_helpful' => $isHelpful,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update counters
            if ($isHelpful) {
                $article->increment('helpful_votes');
                // Fire reputation event for upvote
                event(new ArticleVoted($article, Auth::user(), 'up'));
                
                // Log activity
                UserActivity::log(
                    Auth::id(),
                    'article_voted_helpful',
                    "Hat den Artikel \"{$article->title}\" als hilfreich bewertet",
                    $article
                );
            } else {
                $article->increment('not_helpful_votes');
                // Fire reputation event for downvote
                event(new ArticleVoted($article, Auth::user(), 'down'));
                
                // Log activity
                UserActivity::log(
                    Auth::id(),
                    'article_voted_not_helpful',
                    "Hat den Artikel \"{$article->title}\" als nicht hilfreich bewertet",
                    $article
                );
            }

            return response()->json([
                'success' => true,
                'vote' => $isHelpful ? 'helpful' : 'not_helpful',
                'message' => 'Vote recorded'
            ]);
        }
    }

    /**
     * Report an article
     */
    public function report(Request $request, Article $article): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $userId = Auth::id();

        // Check if user has already reported this article
        $existingReport = DB::table('article_reports')
            ->where('article_id', $article->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'Du hast diesen Artikel bereits gemeldet.'
            ], 400);
        }

        // Create report
        DB::table('article_reports')->insert([
            'article_id' => $article->id,
            'user_id' => $userId,
            'reason' => $validated['reason'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artikel wurde gemeldet. Danke für dein Feedback!'
        ]);
    }

    /**
     * Compare two article revisions
     */
    public function compare(Article $article, Request $request, DiffService $diffService): View
    {
        Gate::authorize('view', $article);

        $request->validate([
            'revisions' => 'required|array|size:2',
            'revisions.*' => 'required|integer|exists:article_revisions,id'
        ]);

        $revisionIds = $request->input('revisions');
        
        // Lade die beiden Revisionen
        $revisions = ArticleRevision::whereIn('id', $revisionIds)
            ->where('article_id', $article->id)
            ->with('user')
            ->get();

        if ($revisions->count() !== 2) {
            abort(404, 'Eine oder beide Revisionen wurden nicht gefunden.');
        }

        // Sortiere nach Versionsnummer (ältere zuerst)
        $revisions = $revisions->sortBy('version_number');
        $oldRevision = $revisions->first();
        $newRevision = $revisions->last();

        // Generiere Diffs für verschiedene Felder
        $titleDiff = $diffService->generateDiff($oldRevision->title, $newRevision->title, 'title');
        $contentDiff = $diffService->generateSynchronizedLineDiff($oldRevision->content, $newRevision->content);
        $excerptDiff = $diffService->generateDiff($oldRevision->excerpt ?? '', $newRevision->excerpt ?? '', 'excerpt');
        
        // Generiere Statistiken
        $stats = $diffService->getDiffStats($oldRevision->content, $newRevision->content);

        return view('wiki.articles.compare', compact(
            'article', 
            'oldRevision', 
            'newRevision', 
            'titleDiff', 
            'contentDiff', 
            'excerptDiff',
            'stats'
        ));
    }
}
