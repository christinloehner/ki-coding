<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller für Benutzer-Management im Wiki-System
 */
class UserController extends Controller
{
    /**
     * Liste aller Benutzer anzeigen
     */
    public function index(Request $request)
    {
        $query = User::with(['roles']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->paginate(20);

        return view('wiki.users.index', compact('users'));
    }

    /**
     * Benutzerprofil anzeigen
     */
    public function show(User $user)
    {
        $articles = Article::where('user_id', $user->id)
            ->published()
            ->with(['category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $comments = Comment::where('user_id', $user->id)
            ->with(['article'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('wiki.users.show', compact('user', 'articles', 'comments'));
    }

    /**
     * Benutzer-Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'articles_count' => Article::where('user_id', $user->id)->count(),
            'comments_count' => Comment::where('user_id', $user->id)->count(),
            'likes_received' => $user->articles()->withCount('likes')->get()->sum('likes_count'),
            'reputation' => $user->reputation,
        ];

        $recentArticles = Article::where('user_id', $user->id)
            ->with(['category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('wiki.users.dashboard', compact('user', 'stats', 'recentArticles'));
    }

    /**
     * Benutzer-Artikel
     */
    public function articles(Request $request)
    {
        $user = Auth::user();
        
        $query = Article::where('user_id', $user->id)
            ->with(['category', 'tags'])
            ->withCount(['comments', 'likes']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('wiki.users.articles', compact('articles'));
    }

    /**
     * Benutzer-Kommentare
     */
    public function comments()
    {
        $user = Auth::user();
        
        $comments = Comment::where('user_id', $user->id)
            ->with(['article'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('wiki.users.comments', compact('comments'));
    }

    /**
     * Benutzer-Lesezeichen
     */
    public function bookmarks()
    {
        $user = Auth::user();
        
        $bookmarks = $user->bookmarks()
            ->with(['category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->orderBy('bookmarks.created_at', 'desc')
            ->paginate(10);

        return view('wiki.users.bookmarks', compact('bookmarks'));
    }

    /**
     * Benutzer-Abonnements
     */
    public function subscriptions()
    {
        $user = Auth::user();
        
        $subscriptions = $user->subscriptions()
            ->with(['category', 'tags'])
            ->withCount(['comments', 'likes'])
            ->orderBy('subscriptions.created_at', 'desc')
            ->paginate(10);

        return view('wiki.users.subscriptions', compact('subscriptions'));
    }

    /**
     * Benutzer-Einstellungen
     */
    public function settings()
    {
        $user = Auth::user();
        
        return view('wiki.users.settings', compact('user'));
    }

    /**
     * Benutzer-Einstellungen aktualisieren
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|max:2048',
            'notification_comments' => 'boolean',
            'notification_articles' => 'boolean',
            'notification_mentions' => 'boolean',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return redirect()->route('wiki.dashboard.settings')
            ->with('success', 'Einstellungen erfolgreich aktualisiert.');
    }

    /**
     * API: Benutzer-Suche
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([]);
        }

        $usersQuery = User::where('name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%');

        // Filter out users who already have the specified role
        if ($request->has('exclude_role')) {
            $roleToExclude = $request->get('exclude_role');
            $usersQuery->whereDoesntHave('roles', function($q) use ($roleToExclude) {
                $q->where('name', $roleToExclude);
            });
        }

        $users = $usersQuery->limit(10)
            ->get(['id', 'name', 'email', 'avatar']);

        return response()->json($users);
    }

    /**
     * API: Benutzer-Aktivität
     */
    public function activity(User $user)
    {
        $activities = collect();

        // Neueste Artikel
        $articles = Article::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($article) {
                return [
                    'type' => 'article',
                    'title' => $article->title,
                    'url' => route('wiki.articles.show', $article->slug),
                    'created_at' => $article->created_at,
                ];
            });

        // Neueste Kommentare
        $comments = Comment::where('user_id', $user->id)
            ->with(['article'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($comment) {
                return [
                    'type' => 'comment',
                    'title' => 'Kommentar zu: ' . $comment->article->title,
                    'url' => route('wiki.articles.show', $comment->article->slug) . '#comment-' . $comment->id,
                    'created_at' => $comment->created_at,
                ];
            });

        $activities = $activities->merge($articles)->merge($comments)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        return response()->json($activities);
    }

    /**
     * Admin: Benutzer-Index
     */
    public function adminIndex(Request $request)
    {
        $query = User::with(['roles'])
            ->withCount(['articles', 'comments']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->paginate(20);

        return view('wiki.admin.users.index', compact('users'));
    }

    /**
     * Admin: Benutzer-Details
     */
    public function adminShow(User $user)
    {
        $user->load(['roles', 'articles', 'comments']);
        
        return view('wiki.admin.users.show', compact('user'));
    }

    /**
     * Admin: Benutzer-Rolle ändern
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->back()
            ->with('success', 'Benutzer-Rolle erfolgreich aktualisiert.');
    }

    /**
     * Admin: Benutzer-Reputation ändern
     */
    public function updateReputation(Request $request, User $user)
    {
        $validated = $request->validate([
            'reputation' => 'required|integer|min:0|max:100000',
        ]);

        $user->update(['reputation' => $validated['reputation']]);

        return redirect()->back()
            ->with('success', 'Benutzer-Reputation erfolgreich aktualisiert.');
    }
}
