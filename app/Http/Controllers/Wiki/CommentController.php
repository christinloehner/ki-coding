<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Article;
use App\Events\CommentCreated;
use App\Events\CommentLiked;
use App\Events\CommentUnliked;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for admin.
     */
    public function index(Request $request): View
    {
        Gate::authorize('moderate', Comment::class);

        $query = Comment::with(['user', 'article'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by article
        if ($request->has('article_id') && $request->article_id) {
            $query->where('article_id', $request->article_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        $comments = $query->paginate(20);

        $articles = Article::published()
            ->select('id', 'title')
            ->orderBy('title')
            ->get();

        return view('wiki.comments.index', compact('comments', 'articles'));
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $article = Article::findOrFail($validated['article_id']);
        
        Gate::authorize('comment', $article);

        // Check if user is banned
        if (Auth::user()->isBanned()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Du bist gesperrt und kannst keine Kommentare schreiben.'
                ], 403);
            }
            return back()->withErrors(['comment' => 'Du bist gesperrt und kannst keine Kommentare schreiben.']);
        }

        // Check if parent comment exists and belongs to the same article
        if (!empty($validated['parent_id'])) {
            $parentComment = Comment::find($validated['parent_id']);
            if (!$parentComment || $parentComment->article_id !== $article->id) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'Ungültiger Elternkommentar.'], 400);
                }
                return back()->withErrors(['comment' => 'Ungültiger Elternkommentar.']);
            }
        }

        // Rate limiting: Check if user has posted too many comments recently
        $recentCommentsCount = Comment::where('user_id', Auth::id())
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->count();

        if ($recentCommentsCount >= 5) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Du hast zu viele Kommentare in kurzer Zeit geschrieben. Bitte warte einen Moment.'
                ], 429);
            }
            return back()->withErrors(['comment' => 'Du hast zu viele Kommentare in kurzer Zeit geschrieben. Bitte warte einen Moment.']);
        }

        $comment = Comment::create([
            'article_id' => $validated['article_id'],
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'status' => Auth::user()->can('approve comments') ? 'approved' : 'pending',
        ]);
        
        // Fire reputation event for comment creation
        event(new CommentCreated($comment));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user'),
                'message' => 'Kommentar wurde erfolgreich erstellt.'
            ]);
        }

        return back()->with('success', 'Kommentar wurde erfolgreich erstellt.');
    }

    /**
     * Display the specified comment.
     */
    public function show(Comment $comment): View
    {
        Gate::authorize('view', $comment);

        $comment->load(['user', 'article', 'replies.user']);

        return view('wiki.comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit(Comment $comment): View
    {
        Gate::authorize('update', $comment);

        return view('wiki.comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment): RedirectResponse|JsonResponse
    {
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->update([
            'content' => $validated['content'],
            'edited_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => 'Kommentar wurde erfolgreich aktualisiert.'
            ]);
        }

        return back()->with('success', 'Kommentar wurde erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): RedirectResponse|JsonResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kommentar wurde erfolgreich gelöscht.'
            ]);
        }

        return back()->with('success', 'Kommentar wurde erfolgreich gelöscht.');
    }

    /**
     * Approve a comment.
     */
    public function approve(Comment $comment): RedirectResponse|JsonResponse
    {
        Gate::authorize('moderate', $comment);

        $comment->update(['status' => 'approved']);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kommentar wurde genehmigt.'
            ]);
        }

        return back()->with('success', 'Kommentar wurde genehmigt.');
    }

    /**
     * Reject a comment.
     */
    public function reject(Comment $comment): RedirectResponse|JsonResponse
    {
        Gate::authorize('moderate', $comment);

        $comment->update(['status' => 'rejected']);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kommentar wurde abgelehnt.'
            ]);
        }

        return back()->with('success', 'Kommentar wurde abgelehnt.');
    }

    /**
     * Mark a comment as spam.
     */
    public function markAsSpam(Comment $comment): RedirectResponse|JsonResponse
    {
        Gate::authorize('moderate', $comment);

        $comment->update(['status' => 'spam']);

        // Decrease user reputation
        $comment->user->decreaseReputation(10);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kommentar wurde als Spam markiert.'
            ]);
        }

        return back()->with('success', 'Kommentar wurde als Spam markiert.');
    }

    /**
     * Report a comment.
     */
    public function report(Request $request, Comment $comment): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Check if user has already reported this comment
        $existingReport = DB::table('comment_reports')
            ->where('comment_id', $comment->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReport) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Du hast diesen Kommentar bereits gemeldet.'
                ], 400);
            }
            return back()->withErrors(['comment' => 'Du hast diesen Kommentar bereits gemeldet.']);
        }

        // Create report
        DB::table('comment_reports')->insert([
            'comment_id' => $comment->id,
            'user_id' => Auth::id(),
            'reason' => $validated['reason'],
            'created_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kommentar wurde gemeldet.'
            ]);
        }

        return back()->with('success', 'Kommentar wurde gemeldet.');
    }

    /**
     * Like a comment.
     */
    public function like(Comment $comment): JsonResponse
    {
        Gate::authorize('view', $comment);

        $userId = Auth::id();

        // Check if user has already liked this comment
        $existingLike = DB::table('comment_likes')
            ->where('comment_id', $comment->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            // Remove like
            DB::table('comment_likes')
                ->where('comment_id', $comment->id)
                ->where('user_id', $userId)
                ->delete();

            $comment->decrement('likes_count');
            $liked = false;
            
            // Fire reputation event for unlike
            event(new CommentUnliked($comment, Auth::user()));
        } else {
            // Add like
            DB::table('comment_likes')->insert([
                'comment_id' => $comment->id,
                'user_id' => $userId,
                'created_at' => now(),
            ]);

            $comment->increment('likes_count');
            $liked = true;
            
            // Fire reputation event for like
            event(new CommentLiked($comment, Auth::user()));
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $comment->likes_count
        ]);
    }

    /**
     * Get comments for an article (AJAX).
     */
    public function getComments(Article $article): JsonResponse
    {
        Gate::authorize('view', $article);

        $comments = $article->comments()
            ->approved()
            ->with(['user', 'replies.user'])
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        return response()->json([
            'comments' => $comments,
            'total_comments' => $article->comments()->approved()->count()
        ]);
    }

    /**
     * Bulk approve comments.
     */
    public function bulkApprove(Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('moderate', Comment::class);

        $validated = $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id',
        ]);

        Comment::whereIn('id', $validated['comment_ids'])
            ->update(['status' => 'approved']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kommentare wurden genehmigt.'
            ]);
        }

        return back()->with('success', 'Kommentare wurden genehmigt.');
    }

    /**
     * Bulk reject comments.
     */
    public function bulkReject(Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('moderate', Comment::class);

        $validated = $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id',
        ]);

        Comment::whereIn('id', $validated['comment_ids'])
            ->update(['status' => 'rejected']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kommentare wurden abgelehnt.'
            ]);
        }

        return back()->with('success', 'Kommentare wurden abgelehnt.');
    }

    /**
     * Bulk delete comments.
     */
    public function bulkDelete(Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('moderate', Comment::class);

        $validated = $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id',
        ]);

        Comment::whereIn('id', $validated['comment_ids'])->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kommentare wurden gelöscht.'
            ]);
        }

        return back()->with('success', 'Kommentare wurden gelöscht.');
    }
}