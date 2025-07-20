<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Honeypot-Middleware zum Schutz vor Spam-Bots
 */
class HoneypotMiddleware
{
    /**
     * Liste der Honeypot-Feldnamen
     */
    private const HONEYPOT_FIELDS = [
        'email_address', // Tarnt sich als Email-Feld
        'phone_number',  // Tarnt sich als Telefon-Feld
        'company_name',  // Tarnt sich als Firma-Feld
        'website_url',   // Tarnt sich als Website-Feld
        'message_body',  // Tarnt sich als Nachricht-Feld
    ];

    /**
     * Minimale Zeit zwischen Seitenaufruf und Formular-Absendung in Sekunden
     */
    private const MIN_FORM_TIME = 3;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nur POST-Requests prüfen
        if ($request->isMethod('POST')) {
            // Honeypot-Felder prüfen
            if ($this->hasHoneypotContent($request)) {
                $this->logSpamAttempt($request, 'honeypot');
                return $this->blockRequest();
            }

            // Zeitbasierte Validierung
            if ($this->isSubmittedTooFast($request)) {
                $this->logSpamAttempt($request, 'too_fast');
                return $this->blockRequest();
            }

            // Verdächtige Patterns prüfen
            if ($this->hasSuspiciousPatterns($request)) {
                $this->logSpamAttempt($request, 'suspicious_patterns');
                return $this->blockRequest();
            }
        }

        return $next($request);
    }

    /**
     * Prüft ob Honeypot-Felder Inhalt haben
     */
    private function hasHoneypotContent(Request $request): bool
    {
        foreach (self::HONEYPOT_FIELDS as $field) {
            if ($request->filled($field)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Prüft ob das Formular zu schnell abgesendet wurde
     */
    private function isSubmittedTooFast(Request $request): bool
    {
        // Timestamp aus verstecktem Feld lesen
        $timestamp = $request->input('form_timestamp');
        
        if (!$timestamp) {
            return true; // Kein Timestamp = verdächtig
        }

        $timeDiff = time() - (int)$timestamp;
        
        return $timeDiff < self::MIN_FORM_TIME;
    }

    /**
     * Prüft auf verdächtige Patterns im Request
     */
    private function hasSuspiciousPatterns(Request $request): bool
    {
        $suspiciousPatterns = [
            // Typische Spam-Wörter
            'viagra', 'cialis', 'casino', 'lottery', 'winner', 'congratulations',
            'million dollars', 'inheritance', 'business proposal', 'urgent response',
            
            // Verdächtige URLs
            'bit.ly', 'tinyurl.com', 'goo.gl', 't.co',
            
            // Verdächtige Zeichen-Kombinationen
            'zyxwvu', 'qwertyuiop', 'asdfghjkl', 'zxcvbnm',
            
            // Übermäßige Großbuchstaben
            'CLICK HERE', 'BUY NOW', 'LIMITED TIME',
            
            // Verdächtige Zeichen
            '₹', '£', '€', '$$$', '!!!',
        ];

        $content = strtolower(implode(' ', $request->all()));
        
        foreach ($suspiciousPatterns as $pattern) {
            if (str_contains($content, strtolower($pattern))) {
                return true;
            }
        }

        // Überprüfung auf zu viele Links
        $linkCount = substr_count($content, 'http://') + substr_count($content, 'https://');
        if ($linkCount > 3) {
            return true;
        }

        // Überprüfung auf zu viele Wiederholungen
        if (preg_match('/(.)\1{10,}/', $content)) {
            return true;
        }

        return false;
    }

    /**
     * Loggt einen Spam-Versuch
     */
    private function logSpamAttempt(Request $request, string $reason): void
    {
        Log::warning('Spam attempt blocked', [
            'reason' => $reason,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->url(),
            'method' => $request->method(),
            'timestamp' => now(),
            'honeypot_fields' => array_intersect_key(
                $request->all(), 
                array_flip(self::HONEYPOT_FIELDS)
            ),
        ]);
    }

    /**
     * Blockiert den Request
     */
    private function blockRequest(): Response
    {
        // Gib eine normale Antwort zurück, um den Bot nicht zu verraten
        return redirect()->back()->with('success', 'Ihre Nachricht wurde erfolgreich gesendet.');
    }
}
