@extends('layouts.app')

@section('title', 'Neuen Artikel erstellen - KI-Coding Wiki')
@section('description', 'Erstelle einen neuen Artikel für die KI-Coding Knowledge Base')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900">
                    Neuen Artikel erstellen
                </h1>
                <p class="mt-2 text-gray-600">
                    Teile dein Wissen mit der Community
                </p>
            </div>
            <a href="{{ route('wiki.articles.index') }}" class="btn-ki-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Zurück zu Artikeln
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('wiki.articles.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6 article-form-section">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Artikel-Details</h2>

            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="form-label">Titel *</label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title') }}"
                           class="form-input {{ $errors->has('title') ? 'border-red-500' : '' }}"
                           placeholder="Gib deinem Artikel einen aussagekräftigen Titel..."
                           required>
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="form-label">Kategorie *</label>
                    <select id="category_id"
                            name="category_id"
                            class="form-input {{ $errors->has('category_id') ? 'border-red-500' : '' }}"
                            required>
                        <option value="">Kategorie auswählen...</option>
                        @foreach($categories as $category)
                            <!-- Hauptkategorie -->
                            <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            
                            <!-- Unterkategorien -->
                            @foreach($category->children as $child)
                                <option value="{{ $child->id }}"
                                        {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;&nbsp;├─ {{ $child->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                <div>
                    <label for="tag-input" class="form-label">Tags</label>
                    <div class="mt-2">
                        <div class="flex flex-wrap gap-2 mb-3" id="selected-tags">
                            <!-- Selected tags will appear here -->
                        </div>
                        <div class="relative">
                            <input type="text"
                                   id="tag-input"
                                   class="form-input {{ $errors->has('tags') ? 'border-red-500' : '' }}"
                                   placeholder="Tag eingeben... (Tab, Komma oder Enter zum Hinzufügen)"
                                   autocomplete="off">
                            <div id="tag-suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto hidden">
                                <!-- Suggestions will appear here -->
                            </div>
                        </div>
                        <!-- Hidden input for form submission -->
                        <input type="hidden" id="tags-data" name="tags" value="{{ old('tags', '') }}">
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        Gib Tags ein und bestätige mit Tab, Komma oder Enter. Bestehende Tags werden automatisch vorgeschlagen.
                    </p>
                    @error('tags')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="form-label">Kurzbeschreibung</label>
                    <textarea id="excerpt"
                              name="excerpt"
                              rows="3"
                              class="form-input {{ $errors->has('excerpt') ? 'border-red-500' : '' }}"
                              placeholder="Kurze Zusammenfassung des Artikels (wird automatisch generiert wenn leer)...">{{ old('excerpt') }}</textarea>
                    @error('excerpt')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm p-6 article-form-section">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Artikel-Inhalt</h2>

            <div class="mb-4">
                <div class="mb-2">
                    <div class="text-sm text-gray-500">
                        Professioneller Markdown-Editor mit Live-Preview und Toolbar-Features
                    </div>
                </div>

                <div class="relative">
                    <x-wiki.editor.markdown-editor
                        name="content"
                        :value="old('content')"
                        placeholder="Schreibe deinen Artikel hier in Markdown..."
                        height="500px"
                        :required="true"
                        id="article-content-editor"
                    />
                </div>

                @error('content')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Article Settings -->
        <div class="bg-white rounded-lg shadow-sm p-6 article-form-section">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Einstellungen</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="form-label">Status *</label>
                    <select id="status"
                            name="status"
                            class="form-input {{ $errors->has('status') ? 'border-red-500' : '' }}"
                            required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                            Entwurf
                        </option>
                        <option value="pending_review" {{ old('status') == 'pending_review' ? 'selected' : '' }}>
                            Zur Überprüfung
                        </option>
                        @can('publish', App\Models\Article::class)
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                Veröffentlicht
                            </option>
                        @endcan
                    </select>
                    @error('status')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured -->
                @can('feature', App\Models\Article::class)
                    <div>
                        <label class="form-label">Optionen</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       name="is_featured"
                                       value="1"
                                       {{ old('is_featured') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Als Featured-Artikel markieren</span>
                            </label>
                        </div>
                    </div>
                @endcan
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-lg shadow-sm p-6 article-form-section">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">SEO-Einstellungen (Optional)</h2>

            <div class="grid grid-cols-1 gap-6">
                <!-- Meta Title -->
                <div>
                    <label for="meta_title" class="form-label">Meta-Titel</label>
                    <input type="text"
                           id="meta_title"
                           name="meta_title"
                           value="{{ old('meta_title') }}"
                           class="form-input {{ $errors->has('meta_title') ? 'border-red-500' : '' }}"
                           placeholder="Wird automatisch vom Artikel-Titel generiert wenn leer...">
                    @error('meta_title')
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
                              placeholder="Wird automatisch generiert wenn leer...">{{ old('meta_description') }}</textarea>
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
                           placeholder="Komma-getrennte Keywords...">
                    @error('meta_keywords')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="{{ route('wiki.articles.index') }}" class="btn-ki-outline">
                Abbrechen
            </a>
            <div class="flex items-center space-x-4">
                <button type="submit" name="action" value="save" class="btn-ki-outline">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    Speichern
                </button>
                <button type="submit" name="action" value="publish" class="btn-ki-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Artikel erstellen
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function togglePreview() {
    const previewArea = document.getElementById('preview-area');
    const content = document.getElementById('content').value;

    if (previewArea.classList.contains('hidden')) {
        if (content.trim()) {
            // Preview-Inhalt laden (später mit AJAX)
            document.getElementById('preview-content').innerHTML = '<p class="text-gray-500">Markdown-Vorschau wird geladen...</p>';
            previewArea.classList.remove('hidden');
        } else {
            alert('Bitte gib erst etwas Inhalt ein.');
        }
    } else {
        previewArea.classList.add('hidden');
    }
}

// Tag-Auswahl-Funktionalität
document.addEventListener('DOMContentLoaded', function() {
    const tagsSelect = document.getElementById('tags');
    const selectedTagsContainer = document.getElementById('selected-tags');

    function updateSelectedTags() {
        selectedTagsContainer.innerHTML = '';
        for (let option of tagsSelect.selectedOptions) {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800';
            tag.innerHTML = `${option.text} <button type="button" onclick="removeTag(${option.value})" class="ml-1 text-indigo-600 hover:text-indigo-800">×</button>`;
            selectedTagsContainer.appendChild(tag);
        }
    }

    tagsSelect.addEventListener('change', updateSelectedTags);
    updateSelectedTags();
});

function removeTag(tagId) {
    const tagsSelect = document.getElementById('tags');
    const option = tagsSelect.querySelector(`option[value="${tagId}"]`);
    if (option) {
        option.selected = false;
        document.getElementById('selected-tags').innerHTML = '';
        for (let option of tagsSelect.selectedOptions) {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800';
            tag.innerHTML = `${option.text} <button type="button" onclick="removeTag(${option.value})" class="ml-1 text-indigo-600 hover:text-indigo-800">×</button>`;
            document.getElementById('selected-tags').appendChild(tag);
        }
    }
}

// Simplified form submission debug
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Looking for form...');
    
    // Find the specific article creation form, not any form
    let form = document.querySelector('form[action*="articles"]');
    if (!form) {
        console.error('Article creation form not found with action selector!');
        // Try alternative selector
        form = document.querySelector('form[method="POST"]:not([action*="logout"])');
        if (!form) {
            console.error('No suitable form found at all!');
            return;
        }
        console.log('Found alternative form:', form);
    } else {
        console.log('Article form found with action selector:', form);
    }
    
    // Add click handlers to buttons directly
    const saveButton = document.querySelector('button[name="action"][value="save"]');
    const publishButton = document.querySelector('button[name="action"][value="publish"]');
    
    if (saveButton) {
        console.log('Save button found, adding click handler');
        saveButton.addEventListener('click', function(e) {
            console.log('Save button clicked!');
            handleFormSubmission(e, 'save');
        });
    } else {
        console.error('Save button not found!');
    }
    
    if (publishButton) {
        console.log('Publish button found, adding click handler');
        publishButton.addEventListener('click', function(e) {
            console.log('Publish button clicked!');
            handleFormSubmission(e, 'publish');
        });
    } else {
        console.error('Publish button not found!');
    }
    
    // Also add form submit handler as backup
    form.addEventListener('submit', function(e) {
        console.log('Form submit event triggered');
        handleFormSubmission(e, 'form');
    });
    
    function handleFormSubmission(e, source) {
        console.log('Handle form submission called from:', source);
        
        try {
            // Sync EasyMDE content
            const easyMdeInstance = window.markdownEditors && window.markdownEditors['article-content-editor'];
            const contentField = document.querySelector('textarea[name="content"]');
            
            if (easyMdeInstance && contentField) {
                const content = easyMdeInstance.value();
                contentField.value = content;
                console.log('EasyMDE content synced:', content ? content.substring(0, 50) + '...' : 'EMPTY');
            }
            
            // Basic validation
            const title = form.querySelector('input[name="title"]');
            const categoryId = form.querySelector('select[name="category_id"]');
            const content = form.querySelector('textarea[name="content"]');
            
            if (!title || !title.value.trim()) {
                alert('Bitte gib einen Titel ein');
                e.preventDefault();
                return false;
            }
            
            if (!categoryId || !categoryId.value) {
                alert('Bitte wähle eine Kategorie aus');
                e.preventDefault();
                return false;
            }
            
            if (!content || !content.value.trim()) {
                alert('Bitte schreibe etwas Inhalt');
                e.preventDefault();
                return false;
            }
            
            console.log('Validation passed, form should submit now...');
            
            // Debug: Show final form data
            const formData = new FormData(form);
            console.log('Final form data:', Object.fromEntries(formData));
            
            // Let the form submit naturally
            return true;
            
        } catch (error) {
            console.error('Error in form submission:', error);
            alert('Ein Fehler ist aufgetreten. Bitte versuche es erneut.');
            e.preventDefault();
            return false;
        }
    }
});

// Tag-Input-System mit Autocomplete
class TagInput {
    constructor() {
        this.tagInput = document.getElementById('tag-input');
        this.selectedTagsContainer = document.getElementById('selected-tags');
        this.suggestionsContainer = document.getElementById('tag-suggestions');
        this.tagsDataInput = document.getElementById('tags-data');
        this.selectedTags = [];
        this.currentSuggestions = [];
        this.selectedSuggestionIndex = -1;
        
        this.initEventListeners();
        this.loadExistingTags();
    }
    
    initEventListeners() {
        this.tagInput.addEventListener('input', (e) => this.handleInput(e));
        this.tagInput.addEventListener('keydown', (e) => this.handleKeydown(e));
        this.tagInput.addEventListener('blur', (e) => this.handleBlur(e));
        
        // Click outside to close suggestions
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#tag-input') && !e.target.closest('#tag-suggestions')) {
                this.hideSuggestions();
            }
        });
    }
    
    async handleInput(e) {
        const value = e.target.value.trim();
        
        if (value.length > 0 && !value.includes(',')) {
            await this.fetchSuggestions(value);
        } else {
            this.hideSuggestions();
        }
        
        // Handle comma separation
        if (value.includes(',')) {
            const tags = value.split(',');
            const lastTag = tags.pop().trim();
            
            // Add all complete tags
            tags.forEach(tag => {
                const trimmedTag = tag.trim();
                if (trimmedTag) {
                    this.addTag(trimmedTag);
                }
            });
            
            // Keep the last incomplete tag in input
            this.tagInput.value = lastTag;
            
            if (lastTag.length > 0) {
                await this.fetchSuggestions(lastTag);
            } else {
                this.hideSuggestions();
            }
        }
    }
    
    handleKeydown(e) {
        const value = this.tagInput.value.trim();
        
        if (e.key === 'Enter' || e.key === 'Tab') {
            e.preventDefault();
            
            if (this.selectedSuggestionIndex >= 0 && this.currentSuggestions[this.selectedSuggestionIndex]) {
                // Add selected suggestion
                this.addTag(this.currentSuggestions[this.selectedSuggestionIndex].name);
            } else if (value) {
                // Add typed tag
                this.addTag(value);
            }
            
            this.tagInput.value = '';
            this.hideSuggestions();
        }
        else if (e.key === 'Backspace' && value === '' && this.selectedTags.length > 0) {
            // Remove last tag if input is empty
            this.removeTag(this.selectedTags[this.selectedTags.length - 1]);
        }
        else if (e.key === 'ArrowDown') {
            e.preventDefault();
            this.selectedSuggestionIndex = Math.min(this.selectedSuggestionIndex + 1, this.currentSuggestions.length - 1);
            this.updateSuggestionHighlight();
        }
        else if (e.key === 'ArrowUp') {
            e.preventDefault();
            this.selectedSuggestionIndex = Math.max(this.selectedSuggestionIndex - 1, -1);
            this.updateSuggestionHighlight();
        }
        else if (e.key === 'Escape') {
            this.hideSuggestions();
        }
    }
    
    handleBlur(e) {
        // Delay to allow suggestion clicks
        setTimeout(() => {
            const value = this.tagInput.value.trim();
            if (value) {
                this.addTag(value);
                this.tagInput.value = '';
            }
            this.hideSuggestions();
        }, 150);
    }
    
    async fetchSuggestions(query) {
        try {
            const response = await fetch(`{{ route('wiki.tags.search') }}?q=${encodeURIComponent(query)}`);
            const suggestions = await response.json();
            
            // Filter out already selected tags
            this.currentSuggestions = suggestions.filter(suggestion => 
                !this.selectedTags.includes(suggestion.name.toLowerCase())
            );
            
            this.showSuggestions();
        } catch (error) {
            console.error('Error fetching tag suggestions:', error);
            this.hideSuggestions();
        }
    }
    
    showSuggestions() {
        if (this.currentSuggestions.length === 0) {
            this.hideSuggestions();
            return;
        }
        
        this.suggestionsContainer.innerHTML = '';
        this.selectedSuggestionIndex = -1;
        
        this.currentSuggestions.forEach((suggestion, index) => {
            const div = document.createElement('div');
            div.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100';
            div.textContent = suggestion.name;
            div.addEventListener('click', () => {
                this.addTag(suggestion.name);
                this.tagInput.value = '';
                this.hideSuggestions();
            });
            
            this.suggestionsContainer.appendChild(div);
        });
        
        this.suggestionsContainer.classList.remove('hidden');
    }
    
    hideSuggestions() {
        this.suggestionsContainer.classList.add('hidden');
        this.selectedSuggestionIndex = -1;
    }
    
    updateSuggestionHighlight() {
        const suggestions = this.suggestionsContainer.children;
        
        Array.from(suggestions).forEach((suggestion, index) => {
            if (index === this.selectedSuggestionIndex) {
                suggestion.classList.add('bg-indigo-100');
            } else {
                suggestion.classList.remove('bg-indigo-100');
            }
        });
    }
    
    addTag(tagName) {
        const normalizedTagName = tagName.toLowerCase().trim();
        
        if (normalizedTagName && !this.selectedTags.includes(normalizedTagName)) {
            this.selectedTags.push(normalizedTagName);
            this.renderSelectedTags();
            this.updateHiddenInput();
        }
    }
    
    removeTag(tagName) {
        const index = this.selectedTags.indexOf(tagName.toLowerCase());
        if (index > -1) {
            this.selectedTags.splice(index, 1);
            this.renderSelectedTags();
            this.updateHiddenInput();
        }
    }
    
    renderSelectedTags() {
        this.selectedTagsContainer.innerHTML = '';
        
        this.selectedTags.forEach(tag => {
            const span = document.createElement('span');
            span.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800';
            span.innerHTML = `
                ${tag}
                <button type="button" class="ml-1 text-indigo-600 hover:text-indigo-800" onclick="tagInput.removeTag('${tag}')">
                    ×
                </button>
            `;
            
            this.selectedTagsContainer.appendChild(span);
        });
    }
    
    updateHiddenInput() {
        this.tagsDataInput.value = this.selectedTags.join(',');
    }
    
    loadExistingTags() {
        const existingTags = this.tagsDataInput.value;
        if (existingTags) {
            const tags = existingTags.split(',').filter(tag => tag.trim());
            tags.forEach(tag => this.addTag(tag.trim()));
        }
    }
}

// Initialize tag input system
document.addEventListener('DOMContentLoaded', function() {
    window.tagInput = new TagInput();
});
</script>
@endpush
@endsection
