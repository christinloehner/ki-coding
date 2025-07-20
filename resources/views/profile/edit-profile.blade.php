@extends('layouts.app')

@section('title', 'Profil bearbeiten')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Profil bearbeiten</h1>
                <p class="mt-2 text-gray-600">Bearbeite deine Profilinformationen und Privatsphäre-Einstellungen.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ $user->username ? route('profile.show', $user->username) : route('profile.show.id', $user->id) }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-eye mr-2"></i>Profil ansehen
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="px-4 py-2 btn-ki-primary focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i>Zurück zum Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                <span class="text-green-800">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                <span class="text-red-800 font-medium">Bitte korrigiere die folgenden Fehler:</span>
            </div>
            <ul class="list-disc list-inside text-red-700 ml-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Information -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Avatar Section -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Avatar</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            <img class="h-24 w-24 rounded-full object-cover" 
                                 src="{{ $user->avatar_url }}" 
                                 alt="{{ $user->name }}">
                        </div>
                        <div class="flex-1">
                            <form action="{{ route('profile.upload-avatar') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="avatar" class="block text-sm font-medium text-gray-700">Neues Avatar-Bild</label>
                                    <input type="file" 
                                           name="avatar" 
                                           id="avatar" 
                                           accept="image/*"
                                           class="mt-1 block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-md file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-primary-50 file:text-primary-700
                                                  hover:file:bg-primary-100">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF bis zu 2MB</p>
                                </div>
                                <div class="flex space-x-3">
                                    <button type="submit" 
                                            class="px-4 py-2 btn-ki-primary text-sm">
                                        <i class="fas fa-upload mr-2"></i>Hochladen
                                    </button>
                                    @if($user->avatar)
                                    <form action="{{ route('profile.remove-avatar') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm"
                                                onclick="return confirm('Avatar wirklich entfernen?')">
                                            <i class="fas fa-trash mr-2"></i>Entfernen
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Grundinformationen</h3>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('profile.update-profile') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700">Biografie</label>
                            <div class="mt-1">
                                <textarea id="bio" 
                                          name="bio" 
                                          rows="10"
                                          placeholder="Erzähle etwas über dich... (Markdown unterstützt)"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ e(old('bio', $user->bio)) }}</textarea>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Du kannst Markdown verwenden für Formatierung.</p>
                        </div>

                        <!-- Personal Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Standort</label>
                                <input type="text" 
                                       name="location" 
                                       id="location" 
                                       value="{{ e(old('location', $user->location)) }}"
                                       placeholder="z.B. Berlin, Deutschland"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                                <input type="url" 
                                       name="website" 
                                       id="website" 
                                       value="{{ e(old('website', $user->website)) }}"
                                       placeholder="https://beispiel.de"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <div>
                                <label for="job_title" class="block text-sm font-medium text-gray-700">Berufsbezeichnung</label>
                                <input type="text" 
                                       name="job_title" 
                                       id="job_title" 
                                       value="{{ e(old('job_title', $user->job_title)) }}"
                                       placeholder="z.B. Software Entwickler*in"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700">Unternehmen</label>
                                <input type="text" 
                                       name="company" 
                                       id="company" 
                                       value="{{ e(old('company', $user->company)) }}"
                                       placeholder="z.B. Tech Company GmbH"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            <div>
                                <label for="birthday" class="block text-sm font-medium text-gray-700">Geburtstag</label>
                                <input type="date" 
                                       name="birthday" 
                                       id="birthday" 
                                       value="{{ e(old('birthday', $user->birthday?->format('Y-m-d'))) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div>
                            <h4 class="text-base font-medium text-gray-900 mb-4">Social Media Profile</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="github_username" class="block text-sm font-medium text-gray-700">
                                        <i class="fab fa-github mr-2"></i>GitHub Username
                                    </label>
                                    <input type="text" 
                                           name="github_username" 
                                           id="github_username" 
                                           value="{{ e(old('github_username', $user->github_username)) }}"
                                           placeholder="username"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>

                                <div>
                                    <label for="twitter_username" class="block text-sm font-medium text-gray-700">
                                        <i class="fab fa-twitter mr-2"></i>Twitter Username
                                    </label>
                                    <input type="text" 
                                           name="twitter_username" 
                                           id="twitter_username" 
                                           value="{{ e(old('twitter_username', $user->twitter_username)) }}"
                                           placeholder="username"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>

                                <div>
                                    <label for="linkedin_username" class="block text-sm font-medium text-gray-700">
                                        <i class="fab fa-linkedin mr-2"></i>LinkedIn Username
                                    </label>
                                    <input type="text" 
                                           name="linkedin_username" 
                                           id="linkedin_username" 
                                           value="{{ e(old('linkedin_username', $user->linkedin_username)) }}"
                                           placeholder="username"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>

                                <div>
                                    <label for="instagram_username" class="block text-sm font-medium text-gray-700">
                                        <i class="fab fa-instagram mr-2"></i>Instagram Username
                                    </label>
                                    <input type="text" 
                                           name="instagram_username" 
                                           id="instagram_username" 
                                           value="{{ e(old('instagram_username', $user->instagram_username)) }}"
                                           placeholder="username"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="px-6 py-2 btn-ki-primary focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <i class="fas fa-save mr-2"></i>Profil speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Privacy Settings Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Privatsphäre</h3>
                    <p class="text-sm text-gray-600">Wähle, welche Informationen öffentlich sichtbar sein sollen.</p>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('profile.update-privacy') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        @php
                            $privacySettings = $user->getPrivacySettings();
                        @endphp

                        <!-- Privacy Settings -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label for="show_bio" class="text-sm font-medium text-gray-700">Biografie</label>
                                <input type="hidden" name="privacy_settings[show_bio]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_bio]" 
                                       id="show_bio" 
                                       value="1"
                                       {{ $privacySettings['show_bio'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_location" class="text-sm font-medium text-gray-700">Standort</label>
                                <input type="hidden" name="privacy_settings[show_location]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_location]" 
                                       id="show_location" 
                                       value="1"
                                       {{ $privacySettings['show_location'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_website" class="text-sm font-medium text-gray-700">Website</label>
                                <input type="hidden" name="privacy_settings[show_website]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_website]" 
                                       id="show_website" 
                                       value="1"
                                       {{ $privacySettings['show_website'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_job_title" class="text-sm font-medium text-gray-700">Beruf</label>
                                <input type="hidden" name="privacy_settings[show_job_title]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_job_title]" 
                                       id="show_job_title" 
                                       value="1"
                                       {{ $privacySettings['show_job_title'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_company" class="text-sm font-medium text-gray-700">Unternehmen</label>
                                <input type="hidden" name="privacy_settings[show_company]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_company]" 
                                       id="show_company" 
                                       value="1"
                                       {{ $privacySettings['show_company'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_birthday" class="text-sm font-medium text-gray-700">Geburtstag</label>
                                <input type="hidden" name="privacy_settings[show_birthday]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_birthday]" 
                                       id="show_birthday" 
                                       value="1"
                                       {{ $privacySettings['show_birthday'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_social_media" class="text-sm font-medium text-gray-700">Social Media</label>
                                <input type="hidden" name="privacy_settings[show_social_media]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_social_media]" 
                                       id="show_social_media" 
                                       value="1"
                                       {{ $privacySettings['show_social_media'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_articles" class="text-sm font-medium text-gray-700">Artikel</label>
                                <input type="hidden" name="privacy_settings[show_articles]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_articles]" 
                                       id="show_articles" 
                                       value="1"
                                       {{ $privacySettings['show_articles'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_reputation" class="text-sm font-medium text-gray-700">Reputation</label>
                                <input type="hidden" name="privacy_settings[show_reputation]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_reputation]" 
                                       id="show_reputation" 
                                       value="1"
                                       {{ $privacySettings['show_reputation'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_joined_date" class="text-sm font-medium text-gray-700">Beitrittsdatum</label>
                                <input type="hidden" name="privacy_settings[show_joined_date]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_joined_date]" 
                                       id="show_joined_date" 
                                       value="1"
                                       {{ $privacySettings['show_joined_date'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_email" class="text-sm font-medium text-gray-700">E-Mail</label>
                                <input type="hidden" name="privacy_settings[show_email]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_email]" 
                                       id="show_email" 
                                       value="1"
                                       {{ $privacySettings['show_email'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="show_last_activity" class="text-sm font-medium text-gray-700">Letzte Aktivität</label>
                                <input type="hidden" name="privacy_settings[show_last_activity]" value="0">
                                <input type="checkbox" 
                                       name="privacy_settings[show_last_activity]" 
                                       id="show_last_activity" 
                                       value="1"
                                       {{ $privacySettings['show_last_activity'] ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>
                        </div>

                        <div class="pt-4 border-t">
                            <button type="submit" 
                                    class="w-full px-4 py-2 btn-ki-secondary focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <i class="fas fa-shield-alt mr-2"></i>Privatsphäre speichern
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Profile Completion -->
            <div class="mt-6 bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Profil-Vollständigkeit</h3>
                </div>
                <div class="px-6 py-4">
                    @php
                        $completionFields = [
                            'name' => 'Name',
                            'bio' => 'Biografie',
                            'avatar' => 'Avatar',
                            'location' => 'Standort',
                            'website' => 'Website'
                        ];
                        $completed = collect($completionFields)->filter(fn($label, $field) => !empty($user->$field))->count();
                        $total = count($completionFields);
                        $percentage = round(($completed / $total) * 100);
                    @endphp
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Vollständigkeit</span>
                            <span class="font-medium">{{ $completed }}/{{ $total }} ({{ $percentage }}%)</span>
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        
                        @if($completed < $total)
                        <div class="text-sm text-gray-600">
                            <p class="mb-2">Noch zu ergänzen:</p>
                            <ul class="space-y-1">
                                @foreach($completionFields as $field => $label)
                                    @if(empty($user->$field))
                                        <li class="flex items-center">
                                            <i class="fas fa-circle text-xs text-gray-400 mr-2"></i>
                                            {{ $label }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- EasyMDE Markdown Editor -->
<link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize EasyMDE for bio field
    if (document.getElementById('bio')) {
        const easyMDE = new EasyMDE({
            element: document.getElementById('bio'),
            spellChecker: false,
            placeholder: 'Erzähle etwas über dich... (Markdown unterstützt)',
            toolbar: [
                'bold', 'italic', 'heading', '|',
                'quote', 'unordered-list', 'ordered-list', '|',
                'link', 'image', '|',
                'preview', 'side-by-side', 'fullscreen', '|',
                'guide'
            ],
            shortcuts: {
                drawTable: 'Cmd-Alt-T'
            },
            insertTexts: {
                horizontalRule: ['', '\n\n-----\n\n'],
                image: ['![](http://', ')'],
                link: ['[', '](http://)'],
                table: ['', '\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n'],
            }
        });
    }
});
</script>
@endpush