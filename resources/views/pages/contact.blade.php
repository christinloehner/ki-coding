@extends('layouts.app')

@section('title', 'Kontakt | KI-Coding - Kostenlose KI-Programmierung Community kontaktieren')
@section('description', 'Kontaktiere die KI-Coding Community! Fragen zu GitHub Copilot Tutorials, ChatGPT Programmierung, Community-Beiträge oder kostenlose KI-Coding Hilfe. Wir antworten schnell!')
@section('keywords', 'KI-Coding Kontakt, KI Programmierung Hilfe, GitHub Copilot Support, ChatGPT Tutorial Hilfe, Community Beiträge, Open Source Contact, AI Coding Fragen, Entwickler Support, Programming Help, KI Tutorial Anfragen')
@section('robots', 'index, follow, max-image-preview:large')

@section('og_title', 'Kontakt | KI-Coding - Kostenlose KI-Programmierung Community')
@section('og_description', 'Kontaktiere die KI-Coding Community! Fragen zu GitHub Copilot, ChatGPT, Community-Beiträge oder kostenlose KI-Coding Hilfe.')
@section('og_type', 'website')
@section('og_image', asset('images/ki-coding-social.jpg'))
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_alt', 'KI-Coding Kontakt - Kostenlose KI-Programmierung Community erreichen')

@section('twitter_title', 'KI-Coding Kontakt: Kostenlose KI-Programmierung Hilfe')
@section('twitter_description', 'Fragen zu GitHub Copilot, ChatGPT, Community-Beiträge oder kostenlose KI-Coding Hilfe? Kontaktiere uns!')
@section('twitter_image', asset('images/ki-coding-social.jpg'))
@section('twitter_image_alt', 'KI-Coding Community Kontakt')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary-50 via-white to-secondary-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-display font-bold text-gray-900 mb-6">
                Kontakt
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
                Hast du Fragen zu unserer Knowledge Base oder möchtest du einen Beitrag vorschlagen? 
                Wir freuen uns auf deine Nachricht und Community-Beiträge!
            </p>
        </div>
    </div>
</section>

<!-- Contact Form & Info Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="contact-form-container bg-gradient-to-br from-primary-50 to-white p-6 md:p-8 rounded-xl shadow-sm overflow-hidden">
                <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">
                    Schreib uns eine Nachricht
                </h2>
                
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="min-w-0">
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Vorname *
                            </label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   required
                                   value="{{ old('first_name') }}"
                                   class="block w-full px-4 py-3 border {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors box-border"
                                   placeholder="Dein Vorname">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="min-w-0">
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nachname *
                            </label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   required
                                   value="{{ old('last_name') }}"
                                   class="block w-full px-4 py-3 border {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors box-border"
                                   placeholder="Dein Nachname">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            E-Mail-Adresse *
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               value="{{ old('email') }}"
                               class="block w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors box-border"
                               placeholder="deine@email.de">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-2">
                            Unternehmen
                        </label>
                        <input type="text" 
                               id="company" 
                               name="company"
                               value="{{ old('company') }}"
                               class="block w-full px-4 py-3 border {{ $errors->has('company') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors box-border"
                               placeholder="Dein Unternehmen (optional)">
                        @error('company')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefon
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone"
                               value="{{ old('phone') }}"
                               class="block w-full px-4 py-3 border {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors box-border"
                               placeholder="Deine Telefonnummer (optional)">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="service" class="block text-sm font-medium text-gray-700 mb-2">
                            Interesse für Themenbereich
                        </label>
                        <select id="service" 
                                name="service"
                                class="block w-full px-4 py-3 border {{ $errors->has('service') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors box-border">
                            <option value="">Bitte wählen (optional)</option>
                            <option value="ki-tools" {{ old('service') == 'ki-tools' ? 'selected' : '' }}>KI-Tools für Entwickler*innen</option>
                            <option value="prompt-engineering" {{ old('service') == 'prompt-engineering' ? 'selected' : '' }}>Prompt Engineering</option>
                            <option value="automation" {{ old('service') == 'automation' ? 'selected' : '' }}>Automatisierung & Workflows</option>
                            <option value="community" {{ old('service') == 'community' ? 'selected' : '' }}>Community & Beiträge</option>
                            <option value="tutorials" {{ old('service') == 'tutorials' ? 'selected' : '' }}>Tutorials & Guides</option>
                            <option value="feedback" {{ old('service') == 'feedback' ? 'selected' : '' }}>Feedback & Verbesserungen</option>
                            <option value="sonstiges" {{ old('service') == 'sonstiges' ? 'selected' : '' }}>Sonstiges</option>
                        </select>
                        @error('service')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Nachricht *
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="5" 
                                  required
                                  class="block w-full px-4 py-3 border {{ $errors->has('message') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors resize-none box-border"
                                  placeholder="Beschreibe dein Anliegen oder deine Fragen...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="privacy" 
                                   name="privacy" 
                                   type="checkbox" 
                                   required
                                   {{ old('privacy') ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="privacy" class="text-gray-700">
                                Ich habe die <a href="{{ route('privacy') }}" class="text-primary-600 hover:text-primary-700 underline">Datenschutzerklärung</a> 
                                gelesen und stimme der Verarbeitung meiner Daten zu. *
                            </label>
                        @error('privacy')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <!-- Google reCAPTCHA -->
                    <div class="flex justify-center">
                        <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.website_key') }}"></div>
                        @error('g-recaptcha-response')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-primary-600 to-secondary-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-primary-700 hover:to-secondary-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Nachricht senden
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">
                        Kontaktinformationen
                    </h2>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">E-Mail</h3>
                                <p class="text-gray-600">info@ki-coding.de</p>
                                <p class="text-gray-600">support@ki-coding.de</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">Adresse</h3>
                                <p class="text-gray-600">
                                    KI-Coding.de<br>
                                    c/o Christin Löhner<br>
                                    Postfach 1119<br>
                                    72187 Vöhringen<br>
                                    Deutschland
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-secondary-50 to-primary-50 p-6 rounded-xl">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        Schnelle Aktionen
                    </h3>
                    <div class="space-y-3">
                        <a href="mailto:info@ki-coding.de?subject=Beitrag%20zur%20Knowledge%20Base" 
                           class="block bg-white p-4 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Beitrag vorschlagen</p>
                                    <p class="text-sm text-gray-600">Direkt per E-Mail senden</p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="https://github.com/christinloehner/ki-coding" target="_blank" rel="noopener noreferrer" 
                           class="block bg-white p-4 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-secondary-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">GitHub Repository</p>
                                    <p class="text-sm text-gray-600">Direkt zu unserem Repo</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- FAQ Preview -->
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        Häufige Fragen
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-1">Wie schnell erhalte ich eine Antwort?</h4>
                            <p class="text-sm text-gray-600">Wir antworten in der Regel innerhalb von 24 Stunden auf alle Anfragen.</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-1">Ist die Knowledge Base wirklich kostenlos?</h4>
                            <p class="text-sm text-gray-600">Ja, alle Inhalte sind 100% kostenlos und für Jede*n verfügbar.</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-1">Kann ich eigene Beiträge einreichen?</h4>
                            <p class="text-sm text-gray-600">Ja, wir freuen uns über Community-Beiträge und Verbesserungsvorschläge!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-primary-600 to-secondary-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-display font-bold text-white mb-6">
            Bereit für den nächsten Schritt?
        </h2>
        <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
            Lass uns gemeinsam herausfinden, wie KI deine Entwicklungsarbeit revolutionieren kann. 
            Teile dein Wissen mit der Community oder entdecke neue Techniken.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="mailto:info@ki-coding.de?subject=Beitrag%20zur%20Knowledge%20Base" 
               class="bg-white text-primary-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                Beitrag vorschlagen
            </a>
            <a href="{{ route('wiki.index') }}" 
               class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-primary-600 transition-all duration-300">
                Knowledge Base ansehen
            </a>
        </div>
    </div>
</section>
@endsection

@push('head')
<style>
/* Contact Form Layout Fixes */
.contact-form-container {
    box-sizing: border-box;
}

.contact-form-container * {
    box-sizing: border-box;
}

.contact-form-field {
    min-width: 0;
    max-width: 100%;
}

/* Ensure reCAPTCHA fits properly on mobile */
.g-recaptcha {
    max-width: 100%;
    overflow: hidden;
}

/* Mobile responsive adjustments */
@media (max-width: 640px) {
    .contact-form-container {
        padding: 1rem;
    }
    
    .g-recaptcha {
        transform: scale(0.85);
        transform-origin: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    });
});
</script>
@endpush

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ContactPage",
    "name": "KI-Coding Kontakt",
    "description": "Kontaktiere die KI-Coding Community für Fragen zu kostenlosen KI-Programmierung Tutorials und Community-Beiträgen",
    "url": "{{ route('contact') }}",
    "mainEntity": {
        "@@type": "Organization",
        "name": "KI-Coding",
        "url": "{{ config('app.url') }}",
        "contactPoint": [
            {
                "@@type": "ContactPoint",
                "contactType": "General Inquiries",
                "email": "info@ki-coding.de",
                "availableLanguage": "German",
                "description": "Allgemeine Anfragen zu KI-Programmierung Tutorials und Community-Beiträgen"
            },
            {
                "@@type": "ContactPoint",
                "contactType": "Technical Support",
                "email": "support@ki-coding.de",
                "availableLanguage": "German",
                "description": "Technischer Support für die Knowledge Base und Tutorials"
            }
        ],
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "c/o Christin Löhner, Postfach 1119",
            "addressLocality": "Vöhringen",
            "postalCode": "72187",
            "addressCountry": "DE"
        }
    },
    "breadcrumb": {
        "@@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ route('home') }}"
            },
            {
                "@@type": "ListItem",
                "position": 2,
                "name": "Kontakt",
                "item": "{{ route('contact') }}"
            }
        ]
    },
    "publisher": {
        "@@type": "Organization",
        "name": "KI-Coding",
        "url": "{{ config('app.url') }}"
    },
    "inLanguage": "de-DE",
    "potentialAction": {
        "@@type": "CommunicateAction",
        "target": {
            "@@type": "EntryPoint",
            "urlTemplate": "{{ route('contact') }}",
            "inLanguage": "de-DE",
            "actionPlatform": [
                "http://schema.org/DesktopWebPlatform",
                "http://schema.org/MobileWebPlatform"
            ]
        }
    }
}
</script>
@endpush