@extends('layouts.app')

@section('title', 'Passwort ändern')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Account-Einstellungen</h1>
                <p class="mt-2 text-gray-600">Verwalte deine Account-Informationen und Sicherheitseinstellungen.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('profile.edit-profile') }}" 
                   class="px-4 py-2 btn-ki-secondary focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <i class="fas fa-user mr-2"></i>Profil bearbeiten
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="px-4 py-2 btn-ki-primary focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i>Zurück zum Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('status'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                <span class="text-green-800">{{ session('status') }}</span>
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

    <div class="space-y-8">
        <!-- Profile Information Update -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Profil-Informationen</h3>
                <p class="text-sm text-gray-600">Aktualisiere deine Account-Informationen und E-Mail-Adresse.</p>
            </div>
            <div class="px-6 py-4">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $user->name) }}"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">E-Mail</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $user->email) }}"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-2">
                                <p class="text-sm text-gray-800">
                                    Deine E-Mail-Adresse ist nicht verifiziert.
                                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Klicke hier, um die Verifizierungs-E-Mail erneut zu senden.
                                    </button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                        Ein neuer Verifizierungslink wurde an deine E-Mail-Adresse gesendet.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 btn-ki-primary focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="fas fa-save mr-2"></i>Speichern
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Update -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Passwort ändern</h3>
                <p class="text-sm text-gray-600">Stelle sicher, dass du ein langes, zufälliges Passwort verwendest, um dein Account sicher zu halten.</p>
            </div>
            <div class="px-6 py-4">
                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Aktuelles Passwort</label>
                        <input type="password" 
                               name="current_password" 
                               id="current_password" 
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Neues Passwort</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Passwort bestätigen</label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 btn-ki-primary focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <i class="fas fa-key mr-2"></i>Passwort aktualisieren
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white shadow-sm rounded-lg border border-red-200">
            <div class="px-6 py-4 border-b border-red-200">
                <h3 class="text-lg font-medium text-red-900">Account löschen</h3>
                <p class="text-sm text-red-600">Sobald dein Account gelöscht ist, werden alle Ressourcen und Daten dauerhaft gelöscht. Bevor du deinen Account löschst, lade bitte alle Daten oder Informationen herunter, die du behalten möchtest.</p>
            </div>
            <div class="px-6 py-4">
                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                    @csrf
                    @method('delete')

                    <div>
                        <label for="password_delete" class="block text-sm font-medium text-gray-700">Passwort zur Bestätigung</label>
                        <input type="password" 
                               name="password" 
                               id="password_delete" 
                               placeholder="Gib dein Passwort ein, um die Löschung zu bestätigen"
                               required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                onclick="return confirm('Bist du sicher, dass du deinen Account löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.')"
                                class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <i class="fas fa-trash mr-2"></i>Account löschen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>
@endsection
