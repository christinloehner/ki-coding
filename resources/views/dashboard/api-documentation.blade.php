@extends('layouts.app')

@section('title', 'API Dokumentation - KI-Coding Wiki')
@section('description', 'Vollständige Dokumentation der KI-Coding Wiki REST API mit Beispielen und Authentifizierung.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-display font-bold text-gray-900 mb-4">
            KI-Coding Wiki REST API v1
        </h1>
        <p class="text-lg text-gray-600">
            Vollständige Dokumentation für den programmatischen Zugriff auf das Wiki über unsere RESTful API.
        </p>
    </div>

    <!-- Inhaltsverzeichnis -->
    <div class="bg-blue-50 rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-blue-900 mb-4">Inhaltsverzeichnis</h2>
        <nav class="space-y-2">
            <a href="#authentication" class="block text-blue-700 hover:text-blue-900">1. Authentifizierung</a>
            <a href="#rate-limiting" class="block text-blue-700 hover:text-blue-900">2. Rate Limiting</a>
            <a href="#response-format" class="block text-blue-700 hover:text-blue-900">3. Response Format</a>
            <a href="#error-handling" class="block text-blue-700 hover:text-blue-900">4. Fehlerbehandlung</a>
            <a href="#endpoints" class="block text-blue-700 hover:text-blue-900">5. API Endpunkte</a>
            <a href="#examples" class="block text-blue-700 hover:text-blue-900">6. Praxisbeispiele</a>
        </nav>
    </div>

    <!-- Authentifizierung -->
    <section id="authentication" class="mb-12">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Authentifizierung</h2>
            
            <div class="prose max-w-none">
                <p class="text-gray-600 mb-4">
                    Die API nutzt Laravel Sanctum für die Authentifizierung mit Bearer Tokens. Du benötigst die Berechtigung <code class="bg-gray-100 px-2 py-1 rounded">"use api"</code> in deinem Account.
                </p>

                <h3 class="text-lg font-semibold text-gray-800 mb-3">Token erstellen</h3>
                <p class="text-gray-600 mb-4">
                    1. Gehe zu <a href="{{ route('dashboard.api-tokens.index') }}" class="text-blue-600 hover:text-blue-800">API Tokens</a><br>
                    2. Erstelle ein neues Token mit einem aussagekräftigen Namen<br>
                    3. Kopiere das angezeigte Token (es wird nur einmal angezeigt!)
                </p>

                <h3 class="text-lg font-semibold text-gray-800 mb-3">Header Format</h3>
                <div class="bg-gray-900 rounded-lg p-4 mb-4">
                    <pre class="text-green-400 text-sm"><code>Authorization: Bearer DEIN_API_TOKEN
Content-Type: application/json
Accept: application/json</code></pre>
                </div>
            </div>
        </div>
    </section>

    <!-- Rate Limiting -->
    <section id="rate-limiting" class="mb-12">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Rate Limiting</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Endpunkt</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Limit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Zeitfenster</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900"><code>/api/v1/*</code></td>
                            <td class="px-6 py-4 text-sm text-gray-600">60 Anfragen</td>
                            <td class="px-6 py-4 text-sm text-gray-600">pro Minute</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900"><code>/api/user</code></td>
                            <td class="px-6 py-4 text-sm text-gray-600">30 Anfragen</td>
                            <td class="px-6 py-4 text-sm text-gray-600">pro Minute</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Response Format -->
    <section id="response-format" class="mb-12">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Response Format</h2>
            
            <div class="prose max-w-none">
                <p class="text-gray-600 mb-4">Alle API-Antworten folgen einem einheitlichen JSON-Format:</p>

                <h3 class="text-lg font-semibold text-gray-800 mb-3">Erfolgreiche Antwort</h3>
                <div class="bg-gray-900 rounded-lg p-4 mb-6">
                    <pre class="text-green-400 text-sm"><code>{
  "success": true,
  "message": "Artikel wurde erfolgreich erstellt.",
  "data": {
    // Antwortdaten hier
  },
  "timestamp": "2025-07-22T14:30:00.000000Z"
}</code></pre>
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-3">Fehler-Antwort</h3>
                <div class="bg-gray-900 rounded-lg p-4 mb-6">
                    <pre class="text-red-400 text-sm"><code>{
  "success": false,
  "message": "Die übermittelten Daten sind ungültig.",
  "errors": {
    "title": ["Das Titel-Feld ist erforderlich."]
  },
  "timestamp": "2025-07-22T14:30:00.000000Z"
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    <!-- Error Handling -->
    <section id="error-handling" class="mb-12">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Fehlerbehandlung</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">HTTP Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bedeutung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Beschreibung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-green-600">200</td>
                            <td class="px-6 py-4 text-sm text-gray-900">OK</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Anfrage erfolgreich verarbeitet</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-green-600">201</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Created</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Ressource erfolgreich erstellt</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-yellow-600">401</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Unauthorized</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Token fehlt oder ungültig</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-yellow-600">403</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Forbidden</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Keine Berechtigung für diese Aktion</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-red-600">422</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Validation Error</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Eingabedaten sind ungültig</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-red-600">429</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Too Many Requests</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Rate Limit überschritten</td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono text-red-600">500</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Server Error</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Interner Serverfehler</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- API Endpunkte -->
    <section id="endpoints" class="mb-12">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">5. API Endpunkte</h2>

            <!-- User Endpoint -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">GET</span>
                    <code class="text-lg font-mono text-gray-800">/api/user</code>
                </div>
                <p class="text-gray-600 mb-4">Gibt Informationen zum authentifizierten Benutzer zurück.</p>
                
                <h4 class="font-semibold text-gray-800 mb-2">Beispiel-Antwort:</h4>
                <div class="bg-gray-900 rounded-lg p-4 mb-4">
                    <pre class="text-green-400 text-sm"><code>{
  "success": true,
  "data": {
    "id": 1,
    "name": "Max Mustermann",
    "email": "max@example.com",
    "email_verified_at": "2025-01-15T10:30:00.000000Z"
  },
  "message": "Benutzerinformationen erfolgreich abgerufen.",
  "timestamp": "2025-07-22T14:30:00.000000Z"
}</code></pre>
                </div>
            </div>

            <!-- Articles Endpoint -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">POST</span>
                    <code class="text-lg font-mono text-gray-800">/api/v1/articles</code>
                </div>
                <p class="text-gray-600 mb-4">Erstellt einen neuen Artikel.</p>
                
                <h4 class="font-semibold text-gray-800 mb-2">Erforderliche Parameter:</h4>
                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Parameter</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Typ</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Erforderlich</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Beschreibung</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-2 text-sm font-mono text-gray-900">title</td>
                                <td class="px-4 py-2 text-sm text-gray-600">string</td>
                                <td class="px-4 py-2 text-sm text-red-600">Ja</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Titel des Artikels (max. 255 Zeichen)</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm font-mono text-gray-900">content</td>
                                <td class="px-4 py-2 text-sm text-gray-600">string</td>
                                <td class="px-4 py-2 text-sm text-red-600">Ja</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Inhalt des Artikels (Markdown/HTML)</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm font-mono text-gray-900">category_slug</td>
                                <td class="px-4 py-2 text-sm text-gray-600">string</td>
                                <td class="px-4 py-2 text-sm text-red-600">Ja</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Slug der Kategorie</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm font-mono text-gray-900">status</td>
                                <td class="px-4 py-2 text-sm text-gray-600">string</td>
                                <td class="px-4 py-2 text-sm text-red-600">Ja</td>
                                <td class="px-4 py-2 text-sm text-gray-600">draft, pending_review oder published</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm font-mono text-gray-900">excerpt</td>
                                <td class="px-4 py-2 text-sm text-gray-600">string</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Nein</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Kurze Beschreibung (max. 500 Zeichen)</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm font-mono text-gray-900">tags</td>
                                <td class="px-4 py-2 text-sm text-gray-600">array</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Nein</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Array mit Tag-Namen</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm font-mono text-gray-900">is_featured</td>
                                <td class="px-4 py-2 text-sm text-gray-600">boolean</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Nein</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Artikel als Featured markieren</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm font-mono text-gray-900">meta_title</td>
                                <td class="px-4 py-2 text-sm text-gray-600">string</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Nein</td>
                                <td class="px-4 py-2 text-sm text-gray-600">SEO Titel (max. 255 Zeichen)</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm font-mono text-gray-900">meta_description</td>
                                <td class="px-4 py-2 text-sm text-gray-600">string</td>
                                <td class="px-4 py-2 text-sm text-gray-600">Nein</td>
                                <td class="px-4 py-2 text-sm text-gray-600">SEO Beschreibung (max. 500 Zeichen)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="font-semibold text-gray-800 mb-2">Beispiel-Anfrage:</h4>
                <div class="bg-gray-900 rounded-lg p-4 mb-4">
                    <pre class="text-blue-400 text-sm"><code>{
  "title": "Einführung in Machine Learning",
  "content": "# Was ist Machine Learning?\n\nMachine Learning ist...",
  "excerpt": "Eine Einführung in die Grundlagen des maschinellen Lernens.",
  "category_slug": "ki-grundlagen",
  "status": "published",
  "tags": ["Machine Learning", "KI", "Grundlagen"],
  "is_featured": true,
  "meta_title": "ML Einführung - KI-Coding Wiki",
  "meta_description": "Lerne die Grundlagen von Machine Learning."
}</code></pre>
                </div>

                <h4 class="font-semibold text-gray-800 mb-2">Erfolgreiche Antwort (201 Created):</h4>
                <div class="bg-gray-900 rounded-lg p-4">
                    <pre class="text-green-400 text-sm"><code>{
  "success": true,
  "message": "Artikel wurde erfolgreich erstellt.",
  "data": {
    "id": 42,
    "title": "Einführung in Machine Learning",
    "slug": "einfuehrung-in-machine-learning",
    "content": "# Was ist Machine Learning?...",
    "excerpt": "Eine Einführung in die Grundlagen...",
    "status": "published",
    "is_featured": true,
    "published_at": "2025-07-22T14:30:00.000000Z",
    "category": {
      "id": 3,
      "name": "KI Grundlagen",
      "slug": "ki-grundlagen"
    },
    "tags": [
      { "id": 15, "name": "machine learning", "slug": "machine-learning" },
      { "id": 8, "name": "ki", "slug": "ki" },
      { "id": 22, "name": "grundlagen", "slug": "grundlagen" }
    ],
    "user": {
      "id": 1,
      "name": "Max Mustermann"
    }
  },
  "timestamp": "2025-07-22T14:30:00.000000Z"
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    <!-- Praxisbeispiele -->
    <section id="examples" class="mb-12">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">6. Praxisbeispiele</h2>

            <!-- cURL Beispiel -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">cURL</h3>
                <div class="bg-gray-900 rounded-lg p-4">
                    <pre class="text-yellow-400 text-sm"><code># Artikel erstellen
curl -X POST {{ config('app.url') }}/api/v1/articles \
  -H "Authorization: Bearer DEIN_API_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "title": "Mein neuer Artikel",
    "content": "Das ist der Inhalt meines Artikels.",
    "category_slug": "tutorials",
    "status": "published"
  }'</code></pre>
                </div>
            </div>

            <!-- PHP Beispiel -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">PHP (Guzzle)</h3>
                <div class="bg-gray-900 rounded-lg p-4">
                    <pre class="text-purple-400 text-sm"><code>&lt;?php
use GuzzleHttp\Client;

$client = new Client();

$response = $client->post('{{ config('app.url') }}/api/v1/articles', [
    'headers' => [
        'Authorization' => 'Bearer DEIN_API_TOKEN',
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ],
    'json' => [
        'title' => 'Mein neuer Artikel',
        'content' => 'Das ist der Inhalt meines Artikels.',
        'category_slug' => 'tutorials',
        'status' => 'published',
        'tags' => ['Tutorial', 'API']
    ]
]);

$data = json_decode($response->getBody(), true);
echo "Artikel erstellt mit ID: " . $data['data']['id'];</code></pre>
                </div>
            </div>

            <!-- Python Beispiel -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Python (requests)</h3>
                <div class="bg-gray-900 rounded-lg p-4">
                    <pre class="text-green-400 text-sm"><code>import requests

url = "{{ config('app.url') }}/api/v1/articles"
headers = {
    "Authorization": "Bearer DEIN_API_TOKEN",
    "Content-Type": "application/json",
    "Accept": "application/json"
}

data = {
    "title": "Mein neuer Artikel",
    "content": "Das ist der Inhalt meines Artikels.",
    "category_slug": "tutorials",
    "status": "published",
    "tags": ["Tutorial", "API"]
}

response = requests.post(url, json=data, headers=headers)

if response.status_code == 201:
    result = response.json()
    print(f"Artikel erstellt mit ID: {result['data']['id']}")
else:
    print(f"Fehler: {response.status_code} - {response.text}")</code></pre>
                </div>
            </div>

            <!-- n8n Beispiel -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">n8n Workflow</h3>
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">HTTP Request Node Konfiguration:</h4>
                    <ul class="list-disc list-inside text-blue-800 space-y-1">
                        <li><strong>Method:</strong> POST</li>
                        <li><strong>URL:</strong> {{ config('app.url') }}/api/v1/articles</li>
                        <li><strong>Headers:</strong>
                            <ul class="list-disc list-inside ml-4 mt-1">
                                <li>Authorization: Bearer DEIN_API_TOKEN</li>
                                <li>Content-Type: application/json</li>
                            </ul>
                        </li>
                        <li><strong>Body:</strong> JSON mit den Artikeldaten</li>
                    </ul>
                </div>
            </div>

            <!-- Sicherheitshinweise -->
            <div class="bg-red-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-900 mb-3">🔒 Sicherheitshinweise</h3>
                <ul class="list-disc list-inside text-red-800 space-y-2">
                    <li>Teile deine API-Tokens niemals öffentlich oder in Code-Repositories</li>
                    <li>Verwende für jede Anwendung separate Tokens</li>
                    <li>Lösche nicht verwendete Tokens regelmäßig</li>
                    <li>Überwache die API-Nutzung auf verdächtige Aktivitäten</li>
                    <li>Nutze HTTPS für alle API-Anfragen</li>
                </ul>
            </div>
        </div>
    </section>
</div>

<style>
.prose code {
    @apply bg-gray-100 text-gray-800 px-1 py-0.5 rounded text-sm;
}
</style>
@endsection