<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Models\ArticleReport;
use App\Models\CommentReport;
use Illuminate\Http\Request;

/**
 * Controller für Moderations-Funktionen
 */
class ModerationController extends Controller
{
    /**
     * Moderation Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'pending_articles' => Article::where('status', 'pending')->count(),
            'flagged_comments' => Comment::where('is_flagged', true)->count(),
            'open_reports' => ArticleReport::where('status', 'pending')->count() + 
                            CommentReport::where('status', 'pending')->count(),
            'banned_users' => User::where('banned_until', '>', now())->count(),
        ];

        return view('wiki.moderation.dashboard', compact('stats'));
    }

    /**
     * Artikel-Moderation
     */
    public function articles(Request $request)
    {
        $query = Article::with(['user', 'category']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('wiki.moderation.articles', compact('articles'));
    }

    /**
     * Artikel genehmigen
     */
    public function approveArticle(Article $article)
    {
        $article->update(['status' => 'published']);

        return redirect()->back()
            ->with('success', 'Artikel wurde genehmigt.');
    }

    /**
     * Artikel ablehnen
     */
    public function rejectArticle(Article $article)
    {
        $article->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Artikel wurde abgelehnt.');
    }

    /**
     * Kommentar-Moderation
     */
    public function comments(Request $request)
    {
        $query = Comment::with(['user', 'article']);

        if ($request->has('flagged')) {
            $query->where('is_flagged', true);
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('wiki.moderation.comments', compact('comments'));
    }

    /**
     * Einfache Placeholder-Methoden für die Routen
     */
    public function pendingArticles() { return $this->articles(); }
    public function flaggedComments() { return $this->comments(); }
    public function approveComment(Comment $comment) { return redirect()->back(); }
    public function rejectComment(Comment $comment) { return redirect()->back(); }
    public function reports() { return view('wiki.moderation.reports'); }
    public function showReport($report) { return view('wiki.moderation.report-detail'); }
    public function resolveReport($report) { return redirect()->back(); }
    public function dismissReport($report) { return redirect()->back(); }
    public function users() { return view('wiki.moderation.users'); }
    public function banUser(User $user) { return redirect()->back(); }
    public function unbanUser(User $user) { return redirect()->back(); }
    public function warnUser(User $user) { return redirect()->back(); }
    public function adminDashboard() { return view('wiki.admin.dashboard'); }
    public function settings() { return view('wiki.admin.settings'); }
    public function updateSettings() { return redirect()->back(); }
    public function analytics() { return view('wiki.admin.analytics'); }
    public function logs() { return view('wiki.admin.logs'); }
    public function statsOverview() { return response()->json([]); }
    public function statsArticles() { return response()->json([]); }
    public function statsUsers() { return response()->json([]); }
}
