<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use App\Mail\ContactFormMail;
use App\Mail\ContactConfirmationMail;
use Exception;

class TestContactFormCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:test {email? : E-Mail-Adresse für den Test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testet das Kontaktformular-E-Mail-System';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 KI-Coding Kontaktformular E-Mail-Test');
        $this->info('==========================================');

        // E-Mail-Adresse von Argument oder Eingabe
        $email = $this->argument('email') ?? $this->ask('📧 Welche E-Mail-Adresse soll getestet werden?');

        if (!$email) {
            $this->error('❌ Keine E-Mail-Adresse angegeben!');
            return 1;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ Ungültige E-Mail-Adresse!');
            return 1;
        }

        $this->info("📨 Teste Kontaktformular-E-Mails für: {$email}");
        $this->newLine();

        // Test-Kontakt erstellen
        $contact = new Contact([
            'first_name' => 'Test',
            'last_name' => 'Benutzer',
            'email' => $email,
            'company' => 'Test-Unternehmen GmbH',
            'phone' => '+49 30 123 456 789',
            'service' => 'KI-Führerschein',
            'message' => 'Das ist eine Test-Nachricht vom automatischen E-Mail-Test-System. Alle Systeme funktionieren ordnungsgemäß! 🚀',
            'privacy_accepted' => true,
            'status' => 'new',
        ]);

        // Temporäre ID für Test-Zwecke
        $contact->id = 999;
        $contact->created_at = now();
        $contact->updated_at = now();

        try {
            $this->info('📤 Sende Admin-Benachrichtigung...');

            // Admin-Benachrichtigung senden
            Mail::to(config('mail.from.address'))->send(new ContactFormMail($contact));

            $this->info('✅ Admin-Benachrichtigung erfolgreich gesendet!');
            $this->newLine();

            $this->info('📤 Sende Benutzer-Bestätigung...');

            // Benutzer-Bestätigung senden
            Mail::to($email)->send(new ContactConfirmationMail($contact));

            $this->info('✅ Benutzer-Bestätigung erfolgreich gesendet!');
            $this->newLine();

            $this->info('🎉 Kontaktformular-E-Mail-Test erfolgreich abgeschlossen!');
            $this->table(['E-Mail-Typ', 'Empfänger', 'Status'], [
                ['Admin-Benachrichtigung', config('mail.from.address'), '✅ Gesendet'],
                ['Benutzer-Bestätigung', $email, '✅ Gesendet'],
            ]);

            $this->newLine();
            $this->info('📝 Nächste Schritte:');
            $this->line('   • Überprüfe das Admin-Postfach: ' . config('mail.from.address'));
            $this->line('   • Überprüfe das Benutzer-Postfach: ' . $email);
            $this->line('   • Schaue auch in den Spam-Ordnern nach');
            $this->line('   • Teste das Kontaktformular über die Website');

            return 0;

        } catch (Exception $e) {
            $this->error('❌ Fehler beim Senden der Kontaktformular-E-Mails:');
            $this->error($e->getMessage());
            $this->newLine();

            $this->warn('🔧 Mögliche Lösungen:');
            $this->line('   • Überprüfe die SMTP-Konfiguration');
            $this->line('   • Stelle sicher, dass der E-Mail-Server erreichbar ist');
            $this->line('   • Überprüfe die E-Mail-Templates');
            $this->line('   • Prüfe die Laravel-Logs für weitere Details');

            return 1;
        }
    }
}
