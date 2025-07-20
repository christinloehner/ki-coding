<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class ArticleManagementController extends Controller
{
    /**
     * Display a listing of all articles for admin management
     */
    public function index(Request $request): View
    {
        Gate::authorize('edit all articles');
        
        $query = Article::with(['user', 'category', 'tags']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by author
        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        
        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $articles = $query->paginate(20)->withQueryString();
        
        // Get filter options
        $categories = Category::all();
        $authors = User::whereHas('articles')->get();
        $statuses = ['draft', 'pending_review', 'published', 'archived'];
        
        return view('admin.articles.index', compact('articles', 'categories', 'authors', 'statuses'));
    }

    /**
     * Bulk update article statuses
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        Gate::authorize('edit all articles');
        
        $request->validate([
            'article_ids' => 'required|array',
            'article_ids.*' => 'exists:articles,id',
            'status' => 'required|in:draft,pending_review,published,archived'
        ]);
        
        $count = Article::whereIn('id', $request->article_ids)
            ->update(['status' => $request->status]);
            
        return back()->with('success', "{$count} articles updated to {$request->status}");
    }

    /**
     * Bulk delete articles
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        Gate::authorize('delete all articles');
        
        $request->validate([
            'article_ids' => 'required|array',
            'article_ids.*' => 'exists:articles,id'
        ]);
        
        $count = Article::whereIn('id', $request->article_ids)->count();
        Article::whereIn('id', $request->article_ids)->delete();
            
        return back()->with('success', "{$count} articles deleted");
    }

    /**
     * Update single article status
     */
    public function updateStatus(Request $request, Article $article): RedirectResponse
    {
        Gate::authorize('edit all articles');
        
        $request->validate([
            'status' => 'required|in:draft,pending_review,published,archived'
        ]);
        
        $oldStatus = $article->status;
        $article->update([
            'status' => $request->status,
            'published_at' => $request->status === 'published' && $oldStatus !== 'published' ? now() : $article->published_at,
        ]);
        
        return back()->with('success', "Article status updated to {$request->status}");
    }

    /**
     * Show pending deletion requests
     */
    public function deletionRequests(): View
    {
        Gate::authorize('delete all articles');
        
        $articles = Article::whereNotNull('deletion_requested_at')
            ->with(['user', 'deletionRequestedByUser', 'category'])
            ->orderBy('deletion_requested_at', 'desc')
            ->paginate(20);
            
        return view('admin.articles.deletion-requests', compact('articles'));
    }
    
    /**
     * Delete article from admin panel
     */
    public function destroy(Article $article)
    {
        $article->delete();
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article "' . $article->title . '" has been deleted successfully.');
    }
}
