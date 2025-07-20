@extends('layouts.app')

@section('title', 'Neue Kategorie erstellen - KI-Coding Wiki')
@section('description', 'Erstelle eine neue Kategorie für die KI-Coding Knowledge Base')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900">
                    Neue Kategorie erstellen
                </h1>
                <p class="mt-2 text-gray-600">
                    Organisiere deine Knowledge Base mit strukturierten Kategorien
                </p>
            </div>
            <a href="{{ route('wiki.categories.index') }}" class="btn-ki-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Zurück zu Kategorien
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('wiki.categories.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Kategorie-Details</h2>

            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="form-label">Kategorie-Name *</label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-input {{ $errors->has('name') ? 'border-red-500' : '' }}"
                           placeholder="z.B. Künstliche Intelligenz, Prompt Engineering..."
                           required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="form-label">Beschreibung</label>
                    <textarea id="description"
                              name="description"
                              rows="3"
                              class="form-input {{ $errors->has('description') ? 'border-red-500' : '' }}"
                              placeholder="Kurze Beschreibung der Kategorie und ihres Inhalts...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Parent Category -->
                <div>
                    <label for="parent_id" class="form-label">Übergeordnete Kategorie</label>
                    <select id="parent_id"
                            name="parent_id"
                            class="form-input {{ $errors->has('parent_id') ? 'border-red-500' : '' }}">
                        <option value="">Hauptkategorie (keine Übergeordnete)</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-sm text-gray-500 mt-1">
                        Erstelle eine Unterkategorie, indem du eine übergeordnete Kategorie auswählst
                    </p>
                    @error('parent_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Visual Settings -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Visuelle Einstellungen</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Color -->
                <div>
                    <label for="color" class="form-label">Farbe</label>
                    <div class="flex items-center space-x-3">
                        <input type="color"
                               id="color"
                               name="color"
                               value="{{ old('color', '#6366f1') }}"
                               class="h-10 w-16 rounded border border-gray-300">
                        <input type="text"
                               id="color-text"
                               name="color_text"
                               value="{{ old('color', '#6366f1') }}"
                               class="form-input flex-1 font-mono text-sm"
                               placeholder="#6366f1">
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        Wähle eine Farbe für die visuelle Darstellung der Kategorie
                    </p>
                    @error('color')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="form-label">Icon (optional)</label>
                    <select id="icon"
                            name="icon"
                            class="form-input {{ $errors->has('icon') ? 'border-red-500' : '' }}">
                        <option value="">Kein Icon</option>
                        <option value="folder" {{ old('icon') == 'folder' ? 'selected' : '' }}>📁 Ordner</option>
                        <option value="code" {{ old('icon') == 'code' ? 'selected' : '' }}>💻 Code</option>
                        <option value="ai" {{ old('icon') == 'ai' ? 'selected' : '' }}>🤖 KI</option>
                        <option value="book" {{ old('icon') == 'book' ? 'selected' : '' }}>📚 Buch</option>
                        <option value="lightbulb" {{ old('icon') == 'lightbulb' ? 'selected' : '' }}>💡 Idee</option>
                        <option value="tools" {{ old('icon') == 'tools' ? 'selected' : '' }}>🛠️ Tools</option>
                        <option value="chart" {{ old('icon') == 'chart' ? 'selected' : '' }}>📊 Statistiken</option>
                        <option value="settings" {{ old('icon') == 'settings' ? 'selected' : '' }}>⚙️ Einstellungen</option>
                    </select>
                    @error('icon')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Preview -->
            <div class="mt-6">
                <label class="form-label">Vorschau</label>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center" id="category-preview">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3"
                             id="preview-icon"
                             style="background-color: #6366f120;">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 7a2 2 0 012-2h10a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900" id="preview-name">Kategorie-Name</h3>
                            <p class="text-sm text-gray-500" id="preview-description">Kategorie-Beschreibung</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">SEO-Einstellungen (Optional)</h2>

            <div class="grid grid-cols-1 gap-6">
                <!-- Slug -->
                <div>
                    <label for="slug" class="form-label">URL-Slug</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            /wiki/category/
                        </span>
                        <input type="text"
                               id="slug"
                               name="slug"
                               value="{{ old('slug') }}"
                               class="form-input rounded-none rounded-r-md {{ $errors->has('slug') ? 'border-red-500' : '' }}"
                               placeholder="wird-automatisch-generiert">
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        Lasse leer für automatische Generierung aus dem Namen
                    </p>
                    @error('slug')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Description -->
                <div>
                    <label for="meta_description" class="form-label">Meta-Beschreibung</label>
                    <textarea id="meta_description"
                              name="meta_description"
                              rows="2"
                              class="form-input {{ $errors->has('meta_description') ? 'border-red-500' : '' }}"
                              placeholder="SEO-optimierte Beschreibung für Suchmaschinen...">{{ old('meta_description') }}</textarea>
                    @error('meta_description')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Meta Keywords -->
                <div>
                    <label for="meta_keywords" class="form-label">Meta-Keywords</label>
                    <input type="text"
                           id="meta_keywords"
                           name="meta_keywords"
                           value="{{ old('meta_keywords') }}"
                           class="form-input {{ $errors->has('meta_keywords') ? 'border-red-500' : '' }}"
                           placeholder="keyword1, keyword2, keyword3">
                    @error('meta_keywords')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Berechtigungen</h2>

            <div class="space-y-4">
                <!-- Visibility -->
                <div>
                    <label for="is_visible" class="form-label">Sichtbarkeit</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox"
                                   name="is_visible"
                                   id="is_visible"
                                   value="1"
                                   {{ old('is_visible', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Kategorie ist öffentlich sichtbar</span>
                        </label>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        Deaktiviere diese Option, um die Kategorie temporär zu verbergen
                    </p>
                </div>

                <!-- Allow Articles -->
                <div>
                    <label for="allow_articles" class="form-label">Artikel-Erstellung</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox"
                                   name="allow_articles"
                                   id="allow_articles"
                                   value="1"
                                   {{ old('allow_articles', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Artikel können in dieser Kategorie erstellt werden</span>
                        </label>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        Deaktiviere diese Option für reine Organisationskategorien
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('wiki.categories.index') }}" class="btn-ki-outline">
                Abbrechen
            </a>
            <div class="flex items-center space-x-4">
                <button type="submit" name="action" value="draft" class="btn-ki-outline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Als Entwurf speichern
                </button>
                <button type="submit" name="action" value="publish" class="btn-ki-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Kategorie erstellen
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live Preview Updates
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const colorInput = document.getElementById('color');
    const colorTextInput = document.getElementById('color-text');
    const slugInput = document.getElementById('slug');

    const previewName = document.getElementById('preview-name');
    const previewDescription = document.getElementById('preview-description');
    const previewIcon = document.getElementById('preview-icon');

    function updatePreview() {
        const name = nameInput.value || 'Kategorie-Name';
        const description = descriptionInput.value || 'Kategorie-Beschreibung';
        const color = colorInput.value || '#6366f1';

        previewName.textContent = name;
        previewDescription.textContent = description;
        previewIcon.style.backgroundColor = color + '20';
        previewIcon.querySelector('svg').style.color = color;
    }

    function generateSlug() {
        if (!slugInput.value && nameInput.value) {
            const slug = nameInput.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            slugInput.value = slug;
        }
    }

    // Color sync
    colorInput.addEventListener('input', function() {
        colorTextInput.value = this.value;
        updatePreview();
    });

    colorTextInput.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            colorInput.value = this.value;
            updatePreview();
        }
    });

    // Live preview
    nameInput.addEventListener('input', function() {
        updatePreview();
        generateSlug();
    });

    descriptionInput.addEventListener('input', updatePreview);

    // Initial preview
    updatePreview();
});
</script>
@endpush
@endsection
