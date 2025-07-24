<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Display search results.
     */
    public function index(Request $request): View
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        $category = $request->get('category');
        $tag = $request->get('tag');
        $sort = $request->get('sort', 'relevance');

        $results = [
            'articles' => collect(),
            'categories' => collect(),
            'tags' => collect(),
            'users' => collect(),
        ];

        if (strlen($query) >= 2) {
            // Search articles
            if ($type === 'all' || $type === 'articles') {
                $results['articles'] = $this->searchArticles($query, $category, $tag, $sort);
            }

            // Search categories
            if ($type === 'all' || $type === 'categories') {
                $results['categories'] = $this->searchCategories($query);
            }

            // Search tags
            if ($type === 'all' || $type === 'tags') {
                $results['tags'] = $this->searchTags($query);
            }

            // Search users
            if ($type === 'all' || $type === 'users') {
                $results['users'] = $this->searchUsers($query);
            }
        }

        $totalResults = $results['articles']->count() + 
                       $results['categories']->count() + 
                       $results['tags']->count() + 
                       $results['users']->count();

        // Kombiniere alle Ergebnisse in eine Collection für das View
        $combinedResults = collect();
        
        // Artikel hinzufügen
        foreach($results['articles'] as $article) {
            $article->type = 'article';
            $article->url = route('wiki.articles.show', $article->slug);
            $combinedResults->push($article);
        }
        
        // Kategorien hinzufügen
        foreach($results['categories'] as $category) {
            $category->type = 'category';
            $category->url = route('wiki.categories.show', $category->slug);
            $category->title = $category->name;
            $category->excerpt = $category->description;
            $combinedResults->push($category);
        }
        
        // Tags hinzufügen
        foreach($results['tags'] as $tag) {
            $tag->type = 'tag';
            $tag->url = route('wiki.tags.show', $tag->slug);
            $tag->title = $tag->name;
            $tag->excerpt = null;
            $combinedResults->push($tag);
        }
        
        // Users hinzufügen
        foreach($results['users'] as $user) {
            $user->type = 'user';
            // Sicherer URL-Building für User
            try {
                if (!empty($user->username)) {
                    $user->url = route('users.show', $user->username);
                } else {
                    $user->url = route('profile.show.id', $user->id);
                }
            } catch (\Exception $e) {
                // Fallback auf Profile-Route mit ID wenn Route-Generation fehlschlägt
                $user->url = route('profile.show.id', $user->id);
            }
            $user->title = $user->name ?? 'Unbekannter User';
            $user->excerpt = $user->bio ?? '';
            $combinedResults->push($user);
        }

        $categories = Category::active()->root()->get();
        $popularTags = Tag::popular()->take(20)->get();

        return view('wiki.search.index', [
            'query' => $query,
            'type' => $type,
            'category' => $category,
            'tag' => $tag,
            'sort' => $sort,
            'results' => $combinedResults,
            'totalResults' => $totalResults,
            'categories' => $categories,
            'popularTags' => $popularTags
        ]);
    }

    /**
     * AJAX search suggestions.
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = [];

        // Article suggestions
        $articles = Article::published()
            ->where('title', 'like', '%' . $query . '%')
            ->select('id', 'title', 'slug')
            ->take(5)
            ->get();

        foreach ($articles as $article) {
            $suggestions[] = [
                'type' => 'article',
                'title' => $article->title,
                'url' => route('wiki.articles.show', $article->slug),
                'icon' => 'document-text'
            ];
        }

        // Category suggestions
        $categories = Category::active()
            ->where('name', 'like', '%' . $query . '%')
            ->select('id', 'name', 'slug')
            ->take(3)
            ->get();

        foreach ($categories as $category) {
            $suggestions[] = [
                'type' => 'category',
                'title' => $category->name,
                'url' => route('wiki.categories.show', $category->slug),
                'icon' => 'folder'
            ];
        }

        // Tag suggestions
        $tags = Tag::where('name', 'like', '%' . $query . '%')
            ->select('id', 'name', 'slug')
            ->take(3)
            ->get();

        foreach ($tags as $tag) {
            $suggestions[] = [
                'type' => 'tag',
                'title' => $tag->name,
                'url' => route('wiki.articles.index', ['tag' => $tag->slug]),
                'icon' => 'tag'
            ];
        }

        return response()->json($suggestions);
    }

    /**
     * Advanced search.
     */
    public function advanced(Request $request): View
    {
        $query = $request->get('q', '');
        $title = $request->get('title', '');
        $content = $request->get('content', '');
        $author = $request->get('author', '');
        $category = $request->get('category');
        $tags = $request->get('tags', []);
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $sort = $request->get('sort', 'relevance');

        $results = collect();

        if ($query || $title || $content || $author || $category || $tags || $dateFrom || $dateTo) {
            $results = $this->advancedSearchArticles(
                $query, $title, $content, $author, $category, $tags, $dateFrom, $dateTo, $sort
            );
        }

        $categories = Category::active()->root()->get();
        $popularTags = Tag::popular()->take(50)->get();
        $authors = User::active()
            ->whereHas('articles', function ($query) {
                $query->where('status', 'published');
            })
            ->select('id', 'name', 'username')
            ->orderBy('name')
            ->get();

        return view('wiki.search.advanced', compact(
            'query', 
            'title', 
            'content', 
            'author', 
            'category', 
            'tags', 
            'dateFrom', 
            'dateTo', 
            'sort',
            'results',
            'categories',
            'popularTags',
            'authors'
        ));
    }

    /**
     * Search articles using Scout full-text search.
     */
    private function searchArticles(string $query, ?string $category = null, ?string $tag = null, string $sort = 'relevance')
    {
        if (empty($query)) {
            $articlesQuery = Article::published()
                ->with(['user', 'category', 'tags']);
        } else {
            // Use Scout for full-text search (without filtering - do it after)
            $articlesQuery = Article::search($query);
        }

        // For Scout queries, we need to get the results first, then filter
        if (!empty($query)) {
            $results = $articlesQuery->get();
            
            // Filter by status after Scout search (Meilisearch doesn't have status as filterable)
            $results = $results->filter(function ($article) {
                return $article->status === 'published';
            });
            
            // Load relationships after Scout search
            $results->load(['user', 'category', 'tags']);
            
            // Filter by category
            if ($category) {
                $results = $results->filter(function ($article) use ($category) {
                    return $article->category && $article->category->slug === $category;
                });
            }

            // Filter by tag
            if ($tag) {
                $results = $results->filter(function ($article) use ($tag) {
                    return $article->tags->contains('slug', $tag);
                });
            }

            // Sort results
            switch ($sort) {
                case 'newest':
                    $results = $results->sortByDesc('published_at');
                    break;
                case 'oldest':
                    $results = $results->sortBy('published_at');
                    break;
                case 'popular':
                    $results = $results->sortByDesc('views_count');
                    break;
                case 'relevance':
                default:
                    // Scout already sorts by relevance
                    break;
            }

            // Convert to paginated results
            $currentPage = request()->get('page', 1);
            $perPage = 10;
            $offset = ($currentPage - 1) * $perPage;
            
            $paginatedResults = $results->slice($offset, $perPage)->values();
            
            return new \Illuminate\Pagination\LengthAwarePaginator(
                $paginatedResults,
                $results->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            // Fallback to Eloquent for empty queries
            
            // Filter by category
            if ($category) {
                $articlesQuery->whereHas('category', function ($q) use ($category) {
                    $q->where('slug', $category);
                });
            }

            // Filter by tag
            if ($tag) {
                $articlesQuery->whereHas('tags', function ($q) use ($tag) {
                    $q->where('slug', $tag);
                });
            }

            // Sort results
            switch ($sort) {
                case 'newest':
                    $articlesQuery->latest('published_at');
                    break;
                case 'oldest':
                    $articlesQuery->oldest('published_at');
                    break;
                case 'popular':
                    $articlesQuery->orderBy('views_count', 'desc');
                    break;
                case 'relevance':
                default:
                    $articlesQuery->latest('published_at');
                    break;
            }

            return $articlesQuery->paginate(10);
        }
    }

    /**
     * Search categories.
     */
    private function searchCategories(string $query)
    {
        return Category::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->withCount(['articles' => function ($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Search tags.
     */
    private function searchTags(string $query)
    {
        return Tag::where('name', 'like', '%' . $query . '%')
            ->orderBy('usage_count', 'desc')
            ->get();
    }

    /**
     * Search users.
     */
    private function searchUsers(string $query)
    {
        return User::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('username', 'like', '%' . $query . '%')
                  ->orWhere('bio', 'like', '%' . $query . '%');
            })
            ->withCount(['publishedArticles', 'approvedComments'])
            ->orderBy('reputation_score', 'desc')
            ->get();
    }

    /**
     * Advanced search for articles.
     */
    private function advancedSearchArticles(
        ?string $query = null,
        ?string $title = null,
        ?string $content = null,
        ?string $author = null,
        ?string $category = null,
        array $tags = [],
        ?string $dateFrom = null,
        ?string $dateTo = null,
        string $sort = 'relevance'
    ) {
        $articlesQuery = Article::published()
            ->with(['user', 'category', 'tags']);

        // General query
        if ($query) {
            $articlesQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('content', 'like', '%' . $query . '%')
                  ->orWhere('excerpt', 'like', '%' . $query . '%');
            });
        }

        // Title search
        if ($title) {
            $articlesQuery->where('title', 'like', '%' . $title . '%');
        }

        // Content search
        if ($content) {
            $articlesQuery->where('content', 'like', '%' . $content . '%');
        }

        // Author search
        if ($author) {
            $articlesQuery->whereHas('user', function ($q) use ($author) {
                $q->where('name', 'like', '%' . $author . '%')
                  ->orWhere('username', 'like', '%' . $author . '%');
            });
        }

        // Category filter
        if ($category) {
            $articlesQuery->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // Tags filter
        if (!empty($tags)) {
            $articlesQuery->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('slug', $tags);
            });
        }

        // Date range filter
        if ($dateFrom) {
            $articlesQuery->where('published_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $articlesQuery->where('published_at', '<=', $dateTo . ' 23:59:59');
        }

        // Sort results
        switch ($sort) {
            case 'newest':
                $articlesQuery->latest('published_at');
                break;
            case 'oldest':
                $articlesQuery->oldest('published_at');
                break;
            case 'popular':
                $articlesQuery->orderBy('views_count', 'desc');
                break;
            case 'relevance':
            default:
                // Calculate relevance score based on multiple factors
                $relevanceConditions = [];
                $relevanceParams = [];
                
                if ($query) {
                    $relevanceConditions[] = '(CASE WHEN title LIKE ? THEN 10 ELSE 0 END)';
                    $relevanceConditions[] = '(CASE WHEN content LIKE ? THEN 2 ELSE 0 END)';
                    $relevanceConditions[] = '(CASE WHEN excerpt LIKE ? THEN 3 ELSE 0 END)';
                    $relevanceParams = array_merge($relevanceParams, [
                        '%' . $query . '%',
                        '%' . $query . '%',
                        '%' . $query . '%'
                    ]);
                }
                
                if ($title) {
                    $relevanceConditions[] = '(CASE WHEN title LIKE ? THEN 15 ELSE 0 END)';
                    $relevanceParams[] = '%' . $title . '%';
                }
                
                if ($content) {
                    $relevanceConditions[] = '(CASE WHEN content LIKE ? THEN 5 ELSE 0 END)';
                    $relevanceParams[] = '%' . $content . '%';
                }
                
                if (!empty($relevanceConditions)) {
                    $articlesQuery->selectRaw('articles.*, (' . implode(' + ', $relevanceConditions) . ') as relevance_score', $relevanceParams)
                        ->orderBy('relevance_score', 'desc');
                } else {
                    $articlesQuery->latest('published_at');
                }
                break;
        }

        return $articlesQuery->paginate(10);
    }

    /**
     * Get search statistics.
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_articles' => Article::published()->count(),
            'total_categories' => Category::active()->count(),
            'total_tags' => Tag::count(),
            'total_users' => User::active()->count(),
            'popular_searches' => $this->getPopularSearches(),
            'recent_articles' => Article::published()
                ->with(['user', 'category'])
                ->latest('published_at')
                ->take(5)
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Get popular search terms (would typically be stored in a separate table).
     */
    private function getPopularSearches(): array
    {
        // This would typically come from a search_logs table
        // For now, return some mock data
        return [
            'Laravel',
            'PHP',
            'JavaScript',
            'Vue.js',
            'Docker',
            'MySQL',
            'API',
            'Authentication',
            'Testing',
            'Deployment'
        ];
    }

    /**
     * Export search results.
     */
    public function export(Request $request): Response
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'articles');
        $format = $request->get('format', 'json');

        $results = [];

        if ($type === 'articles') {
            $results = $this->searchArticles($query)->items();
        } elseif ($type === 'categories') {
            $results = $this->searchCategories($query)->toArray();
        } elseif ($type === 'tags') {
            $results = $this->searchTags($query)->toArray();
        } elseif ($type === 'users') {
            $results = $this->searchUsers($query)->toArray();
        }

        $filename = 'search_results_' . date('Y-m-d_H-i-s');

        if ($format === 'csv') {
            return $this->exportToCsv($results, $filename);
        } else {
            return $this->exportToJson($results, $filename);
        }
    }

    /**
     * Export results to CSV.
     */
    private function exportToCsv(array $results, string $filename): Response
    {
        $csv = fopen('php://output', 'w');
        
        // Add CSV headers
        if (!empty($results)) {
            fputcsv($csv, array_keys($results[0]));
        }
        
        // Add data rows
        foreach ($results as $row) {
            fputcsv($csv, $row);
        }
        
        fclose($csv);

        return response()->make('', 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ]);
    }

    /**
     * Export results to JSON.
     */
    private function exportToJson(array $results, string $filename): Response
    {
        return response()->json($results, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '.json"',
        ]);
    }
}