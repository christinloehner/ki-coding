@props([
    'name' => 'content',
    'value' => '',
    'placeholder' => 'Schreibe hier deinen Artikel...',
    'required' => false,
    'height' => '400px',
    'toolbar' => true,
    'preview' => true,
    'spellChecker' => true,
    'id' => null
])

@php
    $editorId = $id ?? 'markdown-editor-' . Str::random(8);
@endphp

<div class="markdown-editor-container" data-editor-id="{{ $editorId }}">
    <textarea
        id="{{ $editorId }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        class="markdown-editor-hidden"
        style="display: none !important; visibility: hidden !important; position: absolute !important; left: -9999px !important; width: 1px !important; height: 1px !important;"
    >{{ old($name, $value) }}</textarea>
</div>

@push('styles')
<!-- EasyMDE CSS - lokale Version für DSGVO-Compliance -->
<link rel="stylesheet" href="{{ asset('css/easymde.min.css') }}">
<!-- Font Awesome - bereits lokal gehostet -->
<link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
<style>
/* Hide original textarea completely */
.markdown-editor-hidden {
    display: none !important;
    visibility: hidden !important;
    position: absolute !important;
    left: -9999px !important;
    width: 1px !important;
    height: 1px !important;
    opacity: 0 !important;
}

/* Custom KI-Coding Styles für EasyMDE */
.EasyMDEContainer {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    overflow: hidden;
    background: white;
}

.EasyMDEContainer:focus-within {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.EasyMDEContainer .editor-toolbar {
    background: #f8fafc !important;
    border-bottom: 1px solid #e5e7eb !important;
    padding: 0.5rem !important;
}

/* Force button visibility with very specific selectors */
.EasyMDEContainer .editor-toolbar button,
.EasyMDEContainer .editor-toolbar a.fa,
.EasyMDEContainer .editor-toolbar button.fa {
    border: 1px solid #d1d5db !important;
    background: #ffffff !important;
    padding: 0.375rem !important;
    margin: 0 0.125rem !important;
    border-radius: 0.375rem !important;
    color: #374151 !important;
    transition: all 0.2s ease !important;
    opacity: 1 !important;
    visibility: visible !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 32px !important;
    min-height: 32px !important;
}

/* Target Font Awesome icons specifically */
.EasyMDEContainer .editor-toolbar button i,
.EasyMDEContainer .editor-toolbar button i.fa,
.EasyMDEContainer .editor-toolbar button::before,
.EasyMDEContainer .editor-toolbar a::before,
.EasyMDEContainer .editor-toolbar .fa::before {
    color: #111827 !important;
    opacity: 1 !important;
    visibility: visible !important;
    font-weight: bold !important;
}

.EasyMDEContainer .editor-toolbar button:hover,
.EasyMDEContainer .editor-toolbar a.fa:hover,
.EasyMDEContainer .editor-toolbar button.fa:hover {
    background: #e5e7eb !important;
    color: #111827 !important;
    border-color: #9ca3af !important;
}

.EasyMDEContainer .editor-toolbar button:hover i,
.EasyMDEContainer .editor-toolbar button:hover::before,
.EasyMDEContainer .editor-toolbar a:hover::before {
    color: #111827 !important;
}

.EasyMDEContainer .editor-toolbar button.active,
.EasyMDEContainer .editor-toolbar a.fa.active,
.EasyMDEContainer .editor-toolbar button.fa.active {
    background: #6366f1 !important;
    color: white !important;
    border-color: #4f46e5 !important;
}

.EasyMDEContainer .editor-toolbar button.active i,
.EasyMDEContainer .editor-toolbar button.active::before,
.EasyMDEContainer .editor-toolbar a.active::before {
    color: white !important;
}

.EasyMDEContainer .editor-toolbar i.separator {
    border-left: 1px solid #e5e7eb;
    margin: 0 0.5rem;
}

.EasyMDEContainer .CodeMirror {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 14px;
    line-height: 1.6;
    color: #374151;
    background: white;
    border: none;
    padding: 1rem;
    min-height: {{ $height }};
}

.EasyMDEContainer .CodeMirror-cursor {
    border-left: 2px solid #6366f1;
}

.EasyMDEContainer .CodeMirror-selected {
    background: #ddd6fe;
}

.EasyMDEContainer .CodeMirror-line::selection,
.EasyMDEContainer .CodeMirror-line > span::selection,
.EasyMDEContainer .CodeMirror-line > span > span::selection {
    background: #ddd6fe;
}

/* Preview Styles */
.EasyMDEContainer .editor-preview {
    background: white;
    padding: 1rem;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 14px;
    line-height: 1.6;
    color: #374151;
}

.EasyMDEContainer .editor-preview h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #111827;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 0.5rem;
}

.EasyMDEContainer .editor-preview h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #111827;
}

.EasyMDEContainer .editor-preview h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    color: #111827;
}

.EasyMDEContainer .editor-preview p {
    margin-bottom: 1rem;
}

.EasyMDEContainer .editor-preview ul,
.EasyMDEContainer .editor-preview ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.EasyMDEContainer .editor-preview li {
    margin-bottom: 0.25rem;
}

.EasyMDEContainer .editor-preview code {
    background: #f3f4f6;
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-family: 'Fira Code', 'Monaco', 'Consolas', monospace;
    font-size: 0.875rem;
    color: #dc2626;
}

.EasyMDEContainer .editor-preview pre {
    background: #1f2937;
    color: #f9fafb;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin-bottom: 1rem;
}

.EasyMDEContainer .editor-preview pre code {
    background: none;
    color: inherit;
    padding: 0;
    font-size: 0.875rem;
}

.EasyMDEContainer .editor-preview blockquote {
    border-left: 4px solid #6366f1;
    padding-left: 1rem;
    margin: 1rem 0;
    color: #6b7280;
    font-style: italic;
}

.EasyMDEContainer .editor-preview table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.EasyMDEContainer .editor-preview th,
.EasyMDEContainer .editor-preview td {
    border: 1px solid #e5e7eb;
    padding: 0.5rem;
    text-align: left;
}

.EasyMDEContainer .editor-preview th {
    background: #f9fafb;
    font-weight: 600;
}

.EasyMDEContainer .editor-preview a {
    color: #6366f1;
    text-decoration: none;
}

.EasyMDEContainer .editor-preview a:hover {
    text-decoration: underline;
}

/* Fullscreen Mode */
.EasyMDEContainer.fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    border-radius: 0;
}

.EasyMDEContainer.fullscreen .CodeMirror {
    min-height: calc(100vh - 100px);
}

/* Side-by-Side Mode */
.EasyMDEContainer .editor-preview-side {
    border-left: 1px solid #e5e7eb;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .EasyMDEContainer .editor-toolbar {
        flex-wrap: wrap;
        padding: 0.25rem;
    }

    .EasyMDEContainer .editor-toolbar button {
        padding: 0.25rem;
        margin: 0.125rem;
    }

    .EasyMDEContainer .CodeMirror {
        font-size: 16px; /* Prevents zoom on iOS */
        padding: 0.5rem;
    }
}

/* Status Bar */
.EasyMDEContainer .editor-statusbar {
    background: #f8fafc;
    border-top: 1px solid #e5e7eb;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    color: #6b7280;
}

/* Error States */
.EasyMDEContainer.error {
    border-color: #ef4444;
}

.EasyMDEContainer.error:focus-within {
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

/* Success States */
.EasyMDEContainer.success {
    border-color: #10b981;
}

.EasyMDEContainer.success:focus-within {
    border-color: #10b981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* Global EasyMDE overrides for button visibility */
.editor-toolbar {
    background: #f8fafc !important;
}

.editor-toolbar > * {
    color: #111827 !important;
    opacity: 1 !important;
}

.editor-toolbar .fa::before,
.editor-toolbar button::before,
.editor-toolbar a::before {
    color: #111827 !important;
    opacity: 1 !important;
    font-weight: bold !important;
}

.editor-toolbar button,
.editor-toolbar a {
    background: #ffffff !important;
    border: 1px solid #d1d5db !important;
    color: #111827 !important;
}

/* Ultra-specific selectors for all possible icon elements */
.editor-toolbar button *,
.editor-toolbar a *,
.editor-toolbar .fa,
.editor-toolbar .fa-bold,
.editor-toolbar .fa-italic,
.editor-toolbar .fa-header,
.editor-toolbar .fa-quote-left,
.editor-toolbar .fa-list-ul,
.editor-toolbar .fa-list-ol,
.editor-toolbar .fa-link,
.editor-toolbar .fa-image,
.editor-toolbar .fa-code,
.editor-toolbar .fa-table,
.editor-toolbar .fa-eye,
.editor-toolbar .fa-arrows-alt,
.editor-toolbar .fa-question-circle {
    color: #111827 !important;
    opacity: 1 !important;
    font-weight: bold !important;
}

/* Nuclear option - override ALL pseudo-elements */
.editor-toolbar *::before,
.editor-toolbar *::after {
    color: #111827 !important;
    opacity: 1 !important;
    font-weight: bold !important;
}

/* Remove text-shadow */
.editor-toolbar button,
.editor-toolbar a {
    text-shadow: none !important;
}

/* Force inheritance */
.editor-toolbar button,
.editor-toolbar a,
.editor-toolbar button *,
.editor-toolbar a * {
    color: inherit !important;
}

/* Clean icon styling */
.editor-toolbar button i.fa,
.editor-toolbar button i {
    color: #111827 !important;
    opacity: 1 !important;
    font-weight: normal !important;
    display: inline-block !important;
    font-style: normal !important;
}

/* Clean Font Awesome icon display */
.editor-toolbar button i.fa::before {
    color: #111827 !important;
    opacity: 1 !important;
    font-weight: normal !important;
    font-size: 14px !important;
}
</style>
@endpush

@push('scripts')
<!-- EasyMDE JavaScript - lokale Version für DSGVO-Compliance -->
<script src="{{ asset('js/easymde.min.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize EasyMDE for this specific editor
    const editorElement = document.getElementById('{{ $editorId }}');
    if (editorElement) {
        const easymde = new EasyMDE({
            element: editorElement,
            spellChecker: {{ $spellChecker ? 'true' : 'false' }},
            autosave: {
                enabled: true,
                uniqueId: "{{ $editorId }}_autosave",
                delay: 10000,
                submit_delay: 5000,
                timeFormat: {
                    locale: 'de-DE',
                    format: {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    }
                },
                text: "Automatisch gespeichert: "
            },
            placeholder: "{{ $placeholder }}",
            @if(!$toolbar)
            toolbar: false,
            @else
            toolbar: [
                "bold", "italic", "heading", "|",
                "quote", "unordered-list", "ordered-list", "|",
                "link", "image", "table", "|",
                "code", "horizontal-rule", "|",
                @if($preview)
                "preview", "side-by-side", "fullscreen", "|",
                @endif
                "guide",
                {
                    name: "word-count",
                    action: function customFunction(editor) {
                        const text = editor.value();
                        const wordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
                        const charCount = text.length;
                        const readingTime = Math.ceil(wordCount / 200);

                        alert(`Statistiken:\n• Wörter: ${wordCount}\n• Zeichen: ${charCount}\n• Geschätzte Lesezeit: ${readingTime} min`);
                    },
                    className: "fa fa-calculator",
                    title: "Statistiken anzeigen"
                }
            ],
            @endif
            @if($preview)
            previewRender: function(plainText) {
                // Custom preview rendering with syntax highlighting
                return this.parent.markdown(plainText);
            },
            @endif
            status: [
                "autosave",
                "lines",
                "words",
                "cursor",
                {
                    className: "reading-time",
                    defaultValue: function(el) {
                        // Use 'this' context instead of easymde variable
                        if (this && typeof this.value === 'function') {
                            const text = this.value();
                            const wordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
                            const readingTime = Math.ceil(wordCount / 200);
                            el.innerHTML = `📖 ${readingTime} min Lesezeit`;
                        } else {
                            el.innerHTML = `📖 0 min Lesezeit`;
                        }
                    },
                    onUpdate: function(el) {
                        // Use 'this' context instead of easymde variable
                        if (this && typeof this.value === 'function') {
                            const text = this.value();
                            const wordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
                            const readingTime = Math.ceil(wordCount / 200);
                            el.innerHTML = `📖 ${readingTime} min Lesezeit`;
                        } else {
                            el.innerHTML = `📖 0 min Lesezeit`;
                        }
                    }
                }
            ],
            shortcuts: {
                "toggleBold": "Cmd-B",
                "toggleItalic": "Cmd-I",
                "toggleHeading": "Cmd-H",
                "toggleCodeBlock": "Cmd-Alt-C",
                "togglePreview": "Cmd-P",
                "toggleSideBySide": "F9",
                "toggleFullScreen": "F11"
            },
            insertTexts: {
                horizontalRule: ["", "\n\n---\n\n"],
                image: ["![](", ")"],
                link: ["[", "](https://)"],
                table: ["", "\n\n| Spalte 1 | Spalte 2 | Spalte 3 |\n| -------- | -------- | -------- |\n| Text     | Text     | Text     |\n\n"],
            },
            promptURLs: true,
            promptTexts: {
                link: "URL für den Link:",
                image: "URL für das Bild:"
            },
            errorMessages: {
                noFileGiven: "Keine Datei ausgewählt.",
                typeNotAllowed: "Dieser Dateityp ist nicht erlaubt.",
                fileTooLarge: "Datei ist zu groß.",
                importError: "Beim Importieren ist ein Fehler aufgetreten."
            },
            minHeight: "{{ $height }}",
            maxHeight: "800px",
            parsingConfig: {
                allowAtxHeaderWithoutSpace: true,
                strikethrough: true,
                underscoresBreakWords: true,
            },
            renderingConfig: {
                singleLineBreaks: false,
                codeSyntaxHighlighting: true,
            },
            styleSelectedText: true,
            syncScrolling: true,
            tabSize: 4,
            theme: 'ki-coding',
            onToggleFullScreen: function(full) {
                if (full) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                }
            }
        });

        // Custom event listeners
        easymde.codemirror.on('change', function() {
            // Sync content back to original textarea
            editorElement.value = easymde.value();
            
            // Update any form validation
            const event = new Event('input', { bubbles: true });
            editorElement.dispatchEvent(event);
        });

        // Ensure content is synced before form submission
        const form = editorElement.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Sync EasyMDE content to textarea before submission
                editorElement.value = easymde.value();
                console.log('EasyMDE content synced:', editorElement.value.substring(0, 100) + '...');
            });
        }

        // Add paste image support
        easymde.codemirror.on('paste', function(cm, event) {
            const items = event.clipboardData.items;
            for (let i = 0; i < items.length; i++) {
                if (items[i].type.indexOf('image') !== -1) {
                    event.preventDefault();
                    const blob = items[i].getAsFile();
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageUrl = `data:${blob.type};base64,${btoa(e.target.result)}`;
                        const imageText = `![Eingefügtes Bild](${imageUrl})`;
                        easymde.codemirror.replaceSelection(imageText);
                    };
                    reader.readAsBinaryString(blob);
                    break;
                }
            }
        });

        // Ensure original textarea is completely hidden
        editorElement.style.display = 'none';
        editorElement.style.visibility = 'hidden';
        editorElement.style.position = 'absolute';
        editorElement.style.left = '-9999px';

        // Store editor instance globally for potential access
        window.markdownEditors = window.markdownEditors || {};
        window.markdownEditors['{{ $editorId }}'] = easymde;
    }
});
</script>
@endpush
