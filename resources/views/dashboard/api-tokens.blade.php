@extends('layouts.app')

@section('title', 'API Tokens - KI-Coding Wiki')
@section('description', 'Verwalte deine API-Tokens für den programmatischen Zugriff auf das Wiki.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-display font-bold text-gray-900">
            API Tokens
        </h1>
        <a href="{{ route('dashboard.api-documentation') }}" class="btn-ki-secondary">
            📖 API Dokumentation
        </a>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
            <p class="font-bold">Erfolg!</p>
            <p>{{ session('status') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create Token Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Neues Token erstellen</h2>
                <p class="text-sm text-gray-600 mb-4">
                    Gib deinem Token einen aussagekräftigen Namen, damit du es später wiedererkennst.
                </p>
                <form action="{{ route('dashboard.api-tokens.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Token-Name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="z.B. n8n-Agent" required>
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full btn-ki-primary">
                            Token erstellen
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tokens List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Deine Tokens</h2>
                    <p class="text-sm text-gray-600 mt-1">Hier siehst du alle deine erstellten API-Tokens. Du kannst sie hier widerrufen.</p>
                </div>
                
                @if($tokens->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Erstellt am
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Löschen</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($tokens as $token)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $token->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $token->created_at->format('d.m.Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="{{ route('dashboard.api-tokens.destroy', $token->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Bist du sicher, dass du dieses Token widerrufen möchtest? Es kann nicht wiederhergestellt werden.')">Löschen</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7h3a5 5 0 015 5 5 5 0 01-5 5h-3m-6 0H6a5 5 0 01-5-5 5 5 0 015-5h3m-4 5h8"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Tokens gefunden</h3>
                        <p class="mt-1 text-sm text-gray-500">Du hast noch keine API-Tokens erstellt.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
