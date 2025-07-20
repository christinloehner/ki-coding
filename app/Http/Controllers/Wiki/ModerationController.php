<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
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
    
    /**
     * Artikel-Report als erledigt markieren
     */
    public function resolveArticleReport(Request $request, ArticleReport $report)
    {
        $request->validate([
            'status' => 'required|in:reviewed,dismissed'
        ]);
        
        $report->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Kommentar-Report als erledigt markieren
     */
    public function resolveCommentReport(Request $request, CommentReport $report)
    {
        $request->validate([
            'status' => 'required|in:reviewed,dismissed'
        ]);
        
        $report->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function resolveReport($report) { return redirect()->back(); }
    public function dismissReport($report) { return redirect()->back(); }
    public function users() { return view('wiki.moderation.users'); }
    public function banUser(User $user) { return redirect()->back(); }
    public function unbanUser(User $user) { return redirect()->back(); }
    public function warnUser(User $user) { return redirect()->back(); }
    /**
     * Admin Dashboard mit allen Statistics und Reports
     */
    public function adminDashboard() 
    { 
        // Quick Stats
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $totalUsers = User::count();
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))->count();
        $totalCategories = Category::count();
        $activeCategories = Category::whereHas('articles')->count();
        $totalComments = Comment::count();
        $pendingComments = Comment::where('status', 'pending')->count();
        
        // Reports
        $pendingArticleReports = ArticleReport::where('status', 'pending')->count();
        $pendingCommentReports = CommentReport::where('status', 'pending')->count();
        $recentArticleReports = ArticleReport::with(['article', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $recentCommentReports = CommentReport::with(['comment.article', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Recent Activity
        $recentArticles = Article::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        $recentComments = Comment::with(['user', 'article'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Moderation Queue
        $pendingReviews = Article::where('status', 'pending')->count() + 
                         Comment::where('status', 'pending')->count();
        $pendingItems = collect()
            ->merge(Article::where('status', 'pending')->with('user')->get()->map(function($article) {
                $article->type = 'article';
                return $article;
            }))
            ->merge(Comment::where('status', 'pending')->with('user')->get()->map(function($comment) {
                $comment->type = 'comment';
                return $comment;
            }))
            ->sortByDesc('created_at')
            ->take(10);
        
        return view('wiki.admin.dashboard', compact(
            'totalArticles', 'publishedArticles', 'totalUsers', 'activeUsers',
            'totalCategories', 'activeCategories', 'totalComments', 'pendingComments',
            'pendingArticleReports', 'pendingCommentReports',
            'recentArticleReports', 'recentCommentReports',
            'recentArticles', 'recentComments',
            'pendingReviews', 'pendingItems'
        )); 
    }
    public function settings() { return view('wiki.admin.settings'); }
    public function updateSettings() { return redirect()->back(); }
    public function analytics() { return view('wiki.admin.analytics'); }
    public function logs() { return view('wiki.admin.logs'); }
    public function statsOverview() { return response()->json([]); }
    public function statsArticles() { return response()->json([]); }
    public function statsUsers() { return response()->json([]); }
}
