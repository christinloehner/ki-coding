<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = Category::active()
            ->withCount(['articles' => function ($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('sort_order')
            ->get();

        $rootCategories = $categories->whereNull('parent_id')
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        $totalArticles = Article::published()->count();

        return view('wiki.categories.index', compact('categories', 'rootCategories', 'totalArticles'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        Gate::authorize('create', Category::class);

        $categories = Category::active()
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('wiki.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|string|max:7|regex:/^#[0-9a-fA-F]{6}$/',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        // Prevent circular parent relationships
        if ($validated['parent_id']) {
            $parentCategory = Category::find($validated['parent_id']);
            if ($parentCategory && $parentCategory->parent_id) {
                return back()->withErrors(['parent_id' => 'Eine Kategorie kann nicht als Unterordner einer Unterkategorie erstellt werden.']);
            }
        }

        $category = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id'],
            'color' => $validated['color'] ?? '#3B82F6',
            'icon' => $validated['icon'] ?? 'folder',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
        ]);

        return redirect()->route('wiki.categories.show', $category->slug)
            ->with('success', 'Kategorie wurde erfolgreich erstellt.');
    }

    /**
     * Display the specified category with its articles.
     */
    public function show(Category $category, Request $request): View
    {
        Gate::authorize('view', $category);

        $query = $category->articles()
            ->with(['user', 'category', 'tags'])
            ->published();

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

        // Get subcategories
        $subcategories = $category->children()
            ->active()
            ->withCount(['articles' => function ($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('sort_order')
            ->get();

        // Get parent category for breadcrumb
        $parentCategory = $category->parent;

        // Get related categories (siblings)
        $relatedCategories = Category::active()
            ->where('parent_id', $category->parent_id)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        return view('wiki.categories.show', compact(
            'category',
            'articles',
            'subcategories',
            'parentCategory',
            'relatedCategories'
        ));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        Gate::authorize('update', $category);

        $parentCategories = Category::active()
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('wiki.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        Gate::authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'parent_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|string|max:7|regex:/^#[0-9a-fA-F]{6}$/',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        // Prevent circular parent relationships
        if ($validated['parent_id']) {
            if ($validated['parent_id'] === $category->id) {
                return back()->withErrors(['parent_id' => 'Eine Kategorie kann nicht ihr eigener Elternordner sein.']);
            }

            $parentCategory = Category::find($validated['parent_id']);
            if ($parentCategory && $parentCategory->parent_id) {
                return back()->withErrors(['parent_id' => 'Eine Kategorie kann nicht als Unterordner einer Unterkategorie verschoben werden.']);
            }

            // Check if the parent is a child of this category
            if ($parentCategory && $parentCategory->parent_id === $category->id) {
                return back()->withErrors(['parent_id' => 'Eine Kategorie kann nicht unter eine ihrer Unterkategorien verschoben werden.']);
            }
        }

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id'],
            'color' => $validated['color'] ?? $category->color,
            'icon' => $validated['icon'] ?? $category->icon,
            'sort_order' => $validated['sort_order'] ?? $category->sort_order,
            'is_active' => $validated['is_active'] ?? $category->is_active,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
        ]);

        return redirect()->route('wiki.categories.show', $category->slug)
            ->with('success', 'Kategorie wurde erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        Gate::authorize('delete', $category);

        // Check if category has articles
        $articlesCount = $category->articles()->count();
        if ($articlesCount > 0) {
            return back()->withErrors(['category' => 'Diese Kategorie kann nicht gelöscht werden, da sie noch ' . $articlesCount . ' Artikel enthält.']);
        }

        // Check if category has subcategories
        $subcategoriesCount = $category->children()->count();
        if ($subcategoriesCount > 0) {
            return back()->withErrors(['category' => 'Diese Kategorie kann nicht gelöscht werden, da sie noch ' . $subcategoriesCount . ' Unterkategorien enthält.']);
        }

        $category->delete();

        return redirect()->route('wiki.categories.index')
            ->with('success', 'Kategorie wurde erfolgreich gelöscht.');
    }

    /**
     * Update the sort order of categories.
     */
    public function updateSortOrder(Request $request): RedirectResponse
    {
        Gate::authorize('moderate', Category::class);

        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['categories'] as $categoryData) {
                Category::where('id', $categoryData['id'])
                    ->update(['sort_order' => $categoryData['sort_order']]);
            }
        });

        return back()->with('success', 'Kategorie-Reihenfolge wurde erfolgreich aktualisiert.');
    }

    /**
     * Toggle the active status of a category.
     */
    public function toggleActive(Category $category): RedirectResponse
    {
        Gate::authorize('moderate', $category);

        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'aktiviert' : 'deaktiviert';

        return back()->with('success', "Kategorie wurde {$status}.");
    }

    /**
     * Show category management interface for admins.
     */
    public function manage(): View
    {
        Gate::authorize('moderate', Category::class);

        $categories = Category::withCount(['articles' => function ($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('sort_order')
            ->get();

        $rootCategories = $categories->whereNull('parent_id');

        return view('wiki.categories.manage', compact('categories', 'rootCategories'));
    }
}