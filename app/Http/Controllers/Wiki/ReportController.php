<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Models\ArticleReport;
use App\Models\CommentReport;
use App\Models\UserReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller für das Melde-System im Wiki
 */
class ReportController extends Controller
{
    /**
     * Artikel melden
     */
    public function reportArticle(Request $request, Article $article)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'category' => 'required|in:spam,inappropriate,copyright,other',
        ]);

        // Prüfen ob bereits gemeldet
        $existingReport = ArticleReport::where('article_id', $article->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReport) {
            return redirect()->back()
                ->with('error', 'Du hast diesen Artikel bereits gemeldet.');
        }

        ArticleReport::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'reason' => $validated['reason'],
            'category' => $validated['category'],
        ]);

        return redirect()->back()
            ->with('success', 'Artikel wurde erfolgreich gemeldet.');
    }

    /**
     * Kommentar melden
     */
    public function reportComment(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'category' => 'required|in:spam,inappropriate,harassment,other',
        ]);

        // Prüfen ob bereits gemeldet
        $existingReport = CommentReport::where('comment_id', $comment->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReport) {
            return redirect()->back()
                ->with('error', 'Du hast diesen Kommentar bereits gemeldet.');
        }

        CommentReport::create([
            'comment_id' => $comment->id,
            'user_id' => Auth::id(),
            'reason' => $validated['reason'],
            'category' => $validated['category'],
        ]);

        return redirect()->back()
            ->with('success', 'Kommentar wurde erfolgreich gemeldet.');
    }

    /**
     * Benutzer melden
     */
    public function reportUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
            'category' => 'required|in:spam,harassment,impersonation,other',
        ]);

        // Prüfen ob bereits gemeldet
        $existingReport = UserReport::where('reported_user_id', $user->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReport) {
            return redirect()->back()
                ->with('error', 'Du hast diesen Benutzer bereits gemeldet.');
        }

        UserReport::create([
            'reported_user_id' => $user->id,
            'user_id' => Auth::id(),
            'reason' => $validated['reason'],
            'category' => $validated['category'],
        ]);

        return redirect()->back()
            ->with('success', 'Benutzer wurde erfolgreich gemeldet.');
    }
}
