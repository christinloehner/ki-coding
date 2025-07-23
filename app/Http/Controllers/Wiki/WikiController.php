<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WikiController extends Controller
{
    /**
     * Display the wiki homepage.
     */
    public function index(): View
    {
        try {
            $featuredArticles = Article::where('status', 'published')
                ->where('is_featured', true)
                ->with(['user', 'category', 'tags'])
                ->withCount(['comments'])
                ->latest('published_at')
                ->take(6)
                ->get();

            $recentArticles = Article::where('status', 'published')
                ->with(['user', 'category', 'tags'])
                ->withCount(['comments'])
                ->latest('published_at')
                ->take(10)
                ->get();

            $popularArticles = Article::where('status', 'published')
                ->with(['user', 'category', 'tags'])
                ->withCount(['comments'])
                ->orderBy('views_count', 'desc')
                ->take(5)
                ->get();

            $categories = Category::where('is_active', true)
                ->whereNull('parent_id')
                ->with(['children'])
                ->orderBy('sort_order')
                ->get();

            $popularTags = Tag::orderBy('usage_count', 'desc')
                ->take(20)
                ->get();

            $stats = [
                'articles' => Article::where('status', 'published')->count(),
                'categories' => Category::where('is_active', true)->count(),
                'tags' => Tag::count(),
                'contributors' => User::where('is_active', true)->whereHas('articles', function ($query) {
                    $query->where('status', 'published');
                })->count(),
            ];

            return view('wiki.index', compact(
                'featuredArticles',
                'recentArticles',
                'popularArticles',
                'categories',
                'popularTags',
                'stats'
            ));
        } catch (\Exception $e) {
            // Fallback für Debugging
            \Log::error('Wiki Index Error: ' . $e->getMessage());
            
            $featuredArticles = collect();
            $recentArticles = Article::take(10)->get();
            $popularArticles = collect();
            $categories = collect();
            $popularTags = collect();
            $stats = [
                'articles' => Article::count(),
                'categories' => 0,
                'tags' => 0,
                'contributors' => 0,
            ];

            return view('wiki.index', compact(
                'featuredArticles',
                'recentArticles',
                'popularArticles',
                'categories',
                'popularTags',
                'stats'
            ));
        }
    }

    /**
     * Display the wiki dashboard for authenticated users.
     */
    public function dashboard(): View
    {
        $user = auth()->user();
        
        $userArticles = $user->articles()
            ->with(['category', 'tags'])
            ->latest()
            ->paginate(10);

        $userComments = $user->comments()
            ->with(['article'])
            ->latest()
            ->paginate(10);

        $recentChanges = Article::with(['user', 'category'])
            ->latest('updated_at')
            ->take(10)
            ->get();

        $userStats = [
            'articles' => $user->articles()->count(),
            'published_articles' => $user->publishedArticles()->count(),
            'comments' => $user->comments()->count(),
            'reputation' => $user->reputation_score,
        ];

        return view('wiki.dashboard', compact(
            'userArticles',
            'userComments',
            'recentChanges',
            'userStats'
        ));
    }

    /**
     * Display recent changes in the wiki.
     */
    public function recentChanges(): View
    {
        $changes = Article::with(['user', 'category'])
            ->latest('updated_at')
            ->paginate(50);

        return view('wiki.recent-changes', compact('changes'));
    }

    /**
     * Display wiki statistics.
     */
    public function stats(): View
    {
        $stats = [
            'total_articles' => Article::count(),
            'published_articles' => Article::published()->count(),
            'draft_articles' => Article::where('status', 'draft')->count(),
            'pending_articles' => Article::where('status', 'pending_review')->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::active()->count(),
            'total_tags' => Tag::count(),
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
            'contributors' => User::active()->whereHas('articles', function ($query) {
                $query->where('status', 'published');
            })->count(),
        ];

        $topContributors = User::withCount(['publishedArticles', 'approvedComments'])
            ->orderBy('reputation_score', 'desc')
            ->take(10)
            ->get();

        $topCategories = Category::withCount(['publishedArticles'])
            ->orderBy('published_articles_count', 'desc')
            ->take(10)
            ->get();

        $topTags = Tag::orderBy('usage_count', 'desc')
            ->take(20)
            ->get();

        return view('wiki.stats', compact(
            'stats',
            'topContributors',
            'topCategories',
            'topTags'
        ));
    }
}
