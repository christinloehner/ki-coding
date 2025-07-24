<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Controller für Tag-Management im Wiki-System
 */
class TagController extends Controller
{
    /**
     * Liste aller Tags anzeigen
     */
    public function index(Request $request)
    {
        $query = Tag::withCount('articles')
            ->orderBy('articles_count', 'desc');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $tags = $query->paginate(20);

        return view('wiki.tags.index', compact('tags'));
    }

    /**
     * Artikel eines bestimmten Tags anzeigen
     */
    public function show(Tag $tag, Request $request)
    {
        // Query für Artikel mit Filtermöglichkeiten
        $articlesQuery = Article::published()
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->with(['user', 'category', 'tags']);

        // Suchfilter
        if ($request->has('search') && !empty($request->search)) {
            $articlesQuery->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('content', 'like', '%' . $request->search . '%')
                      ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        // Kategoriefilter
        if ($request->has('category') && !empty($request->category)) {
            $articlesQuery->whereHas('category', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        }

        // Sortierung
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $articlesQuery->orderBy('published_at', 'asc');
                break;
            case 'popular':
                $articlesQuery->orderBy('views_count', 'desc');
                break;
            case 'title':
                $articlesQuery->orderBy('title', 'asc');
                break;
            default: // latest
                $articlesQuery->orderBy('published_at', 'desc');
                break;
        }

        $articles = $articlesQuery->withCount(['comments'])->paginate(10);

        // Verwandte Tags finden (Tags die in den gleichen Artikeln verwendet werden)
        $relatedTags = Tag::whereHas('articles', function ($query) use ($tag) {
                $query->whereHas('tags', function ($subQuery) use ($tag) {
                    $subQuery->where('tags.id', $tag->id);
                });
            })
            ->where('id', '!=', $tag->id)
            ->withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->limit(10)
            ->get();

        // Kategorien für Filter
        $categories = \App\Models\Category::whereHas('articles.tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->orderBy('name')
            ->get();

        // Statistiken
        $uniqueAuthors = $articles->pluck('user.id')->unique()->count();
        $uniqueCategories = $articles->pluck('category.id')->unique()->count();

        return view('wiki.tags.show', compact(
            'tag', 
            'articles', 
            'relatedTags', 
            'categories', 
            'uniqueAuthors', 
            'uniqueCategories'
        ));
    }

    /**
     * Tag-Suche für Autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 1) {
            return response()->json([]);
        }
        
        $tags = Tag::where('name', 'like', $query . '%')
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name']);
            
        return response()->json($tags);
    }

    /**
     * Formular zum Erstellen eines neuen Tags
     */
    public function create()
    {
        return view('wiki.tags.create');
    }

    /**
     * Neuen Tag erstellen
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tag = Tag::create($validated);

        return redirect()->route('wiki.tags.show', $tag->slug)
            ->with('success', 'Tag erfolgreich erstellt.');
    }

    /**
     * Tag bearbeiten
     */
    public function edit(Tag $tag)
    {
        return view('wiki.tags.edit', compact('tag'));
    }

    /**
     * Tag aktualisieren
     */
    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name,' . $tag->id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tag->update($validated);

        return redirect()->route('wiki.tags.show', $tag->slug)
            ->with('success', 'Tag erfolgreich aktualisiert.');
    }

    /**
     * Tag löschen
     */
    public function destroy(Tag $tag)
    {
        // Prüfen ob Tag noch verwendet wird
        if ($tag->articles()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tag kann nicht gelöscht werden, da er noch von Artikeln verwendet wird.');
        }

        $tag->delete();

        return redirect()->route('wiki.tags.index')
            ->with('success', 'Tag erfolgreich gelöscht.');
    }


    /**
     * API: Beliebte Tags
     */
    public function popular()
    {
        $tags = Cache::remember('popular_tags', 3600, function () {
            return Tag::withCount('articles')
                ->orderBy('articles_count', 'desc')
                ->limit(20)
                ->get(['id', 'name', 'slug', 'color', 'articles_count']);
        });

        return response()->json($tags);
    }
}
