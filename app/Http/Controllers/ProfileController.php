<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display public user profile.
     */
    public function show(User $user): View
    {
        $user->loadCount(['articles' => function($query) {
            $query->where('status', 'published');
        }]);

        $user->load(['articles' => function($query) {
            $query->where('status', 'published')
                  ->orderBy('created_at', 'desc')
                  ->take(10);
        }]);

        return view('profile.show', compact('user'));
    }

    /**
     * Show profile information edit form.
     */
    public function editProfile(Request $request): View
    {
        Gate::authorize('edit own profile');
        
        return view('profile.edit-profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        Gate::authorize('edit own profile');

        $validated = $request->validate([
            'bio' => 'nullable|string|max:2000',
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'birthday' => 'nullable|date|before:today',
            'github_username' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9\-]+$/',
            'twitter_username' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9_]+$/',
            'linkedin_username' => 'nullable|string|max:255',
            'instagram_username' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9_.]+$/',
        ]);

        $user = $request->user();
        $user->fill($validated);
        $user->save();
        $user->updateProfileCompletion();

        return back()->with('success', 'Profil erfolgreich aktualisiert!');
    }

    /**
     * Update privacy settings.
     */
    public function updatePrivacy(Request $request): RedirectResponse
    {
        Gate::authorize('edit own profile');

        $validated = $request->validate([
            'privacy_settings' => 'required|array',
            'privacy_settings.show_email' => 'boolean',
            'privacy_settings.show_birthday' => 'boolean',
            'privacy_settings.show_location' => 'boolean',
            'privacy_settings.show_website' => 'boolean',
            'privacy_settings.show_job_title' => 'boolean',
            'privacy_settings.show_company' => 'boolean',
            'privacy_settings.show_bio' => 'boolean',
            'privacy_settings.show_social_media' => 'boolean',
            'privacy_settings.show_articles' => 'boolean',
            'privacy_settings.show_reputation' => 'boolean',
            'privacy_settings.show_joined_date' => 'boolean',
            'privacy_settings.show_last_activity' => 'boolean',
        ]);

        $user = $request->user();
        $user->privacy_settings = $validated['privacy_settings'];
        $user->save();

        return back()->with('success', 'Privatsphäre-Einstellungen aktualisiert!');
    }

    /**
     * Upload avatar.
     */
    public function uploadAvatar(Request $request): RedirectResponse
    {
        Gate::authorize('edit own profile');

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        // Store new avatar
        $file = $request->file('avatar');
        $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('avatars', $filename, 'public');

        $user->avatar = $filename;
        $user->save();
        $user->updateProfileCompletion();

        return back()->with('success', 'Avatar erfolgreich hochgeladen!');
    }

    /**
     * Remove avatar.
     */
    public function removeAvatar(Request $request): RedirectResponse
    {
        Gate::authorize('edit own profile');

        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        $user->avatar = null;
        $user->save();
        $user->updateProfileCompletion();

        return back()->with('success', 'Avatar entfernt!');
    }

    /**
     * Update the user's profile information (legacy method).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
