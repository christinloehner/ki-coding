<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Models\UserInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

/**
 * Controller für das Einladungs-System
 */
class InvitationController extends Controller
{
    /**
     * Einladungen auflisten
     */
    public function index(Request $request)
    {
        $query = UserInvitation::with(['invitedBy', 'user']);

        if ($request->has('status')) {
            if ($request->status === 'accepted') {
                $query->whereNotNull('accepted_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('accepted_at')->where('expires_at', '>', now());
            } elseif ($request->status === 'expired') {
                $query->whereNull('accepted_at')->where('expires_at', '<', now());
            }
        }

        $invitations = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('wiki.admin.invitations.index', compact('invitations'));
    }

    /**
     * Einladung erstellen
     */
    public function create()
    {
        return view('wiki.admin.invitations.create');
    }

    /**
     * Einladung speichern
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email|unique:user_invitations,email',
            'name' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        $invitation = UserInvitation::create([
            'email' => $validated['email'],
            'name' => $validated['name'] ?? null,
            'token' => Str::random(64),
            'invited_by' => Auth::id(),
            'expires_at' => now()->addDays(7),
            'metadata' => json_encode([
                'message' => $validated['message'] ?? null,
            ]),
        ]);

        // Email versenden (später implementieren)
        // Mail::to($invitation->email)->send(new InvitationMail($invitation));

        return redirect()->route('wiki.admin.invitations.index')
            ->with('success', 'Einladung wurde erfolgreich versendet.');
    }

    /**
     * Einladung anzeigen
     */
    public function accept(string $token)
    {
        $invitation = UserInvitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->whereNull('accepted_at')
            ->first();

        if (!$invitation) {
            return redirect()->route('login')
                ->with('error', 'Einladung ist ungültig oder abgelaufen.');
        }

        return view('wiki.invitations.accept', compact('invitation'));
    }

    /**
     * Benutzer-Registrierung über Einladung
     */
    public function register(Request $request, string $token)
    {
        $invitation = UserInvitation::where('token', $token)
            ->where('expires_at', '>', now())
            ->whereNull('accepted_at')
            ->first();

        if (!$invitation) {
            return redirect()->route('login')
                ->with('error', 'Einladung ist ungültig oder abgelaufen.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        // Benutzer erstellen
        $user = User::create([
            'name' => $validated['name'],
            'email' => $invitation->email,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
            'reputation' => 0,
        ]);

        // Standardrolle zuweisen
        $user->assignRole('user');

        // Einladung als akzeptiert markieren
        $invitation->update([
            'accepted_at' => now(),
            'user_id' => $user->id,
        ]);

        // Benutzer einloggen
        Auth::login($user);

        return redirect()->route('wiki.dashboard.index')
            ->with('success', 'Willkommen im Wiki! Dein Account wurde erfolgreich erstellt.');
    }

    /**
     * Einladung löschen
     */
    public function destroy(UserInvitation $invitation)
    {
        $invitation->delete();

        return redirect()->route('wiki.admin.invitations.index')
            ->with('success', 'Einladung wurde erfolgreich gelöscht.');
    }

    /**
     * Einladung erneut versenden
     */
    public function resend(UserInvitation $invitation)
    {
        if ($invitation->accepted_at) {
            return redirect()->back()
                ->with('error', 'Einladung wurde bereits akzeptiert.');
        }

        // Neuen Token generieren und Ablaufzeit verlängern
        $invitation->update([
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        // Email versenden (später implementieren)
        // Mail::to($invitation->email)->send(new InvitationMail($invitation));

        return redirect()->back()
            ->with('success', 'Einladung wurde erfolgreich erneut versendet.');
    }
}
