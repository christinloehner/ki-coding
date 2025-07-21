<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Article;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard für authentifizierte Benutzer anzeigen.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        // Benutzer-Statistiken
        $userStats = [
            'articles' => Article::where('user_id', $user->id)->count(),
            'comments' => Comment::where('user_id', $user->id)->count(),
            'views' => Article::where('user_id', $user->id)->sum('views_count') ?? 0,
            'reputation' => $user->reputation_score ?? 0,
            'reputation_level' => $user->reputation_level,
        ];
        
        // Benutzer-Artikel (letzte 5)
        $myArticles = Article::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Neueste Aktivitäten (falls UserActivity Modell existiert)
        $recentActivity = collect();
        
        // Community-Statistiken
        $communityStats = [
            'total_articles' => Article::where('status', 'published')->count(),
            'total_users' => User::count(),
            'total_categories' => Category::count(),
            'total_comments' => Comment::count(),
            'total_views' => Article::sum('views_count') ?? 0,
            'total_tags' => DB::table('tags')->count(),
        ];
        
        return view('dashboard', compact(
            'userStats',
            'myArticles', 
            'recentActivity',
            'communityStats'
        ));
    }
}
