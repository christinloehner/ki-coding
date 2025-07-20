<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use Exception;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email? : E-Mail-Adresse für den Test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testet das E-Mail-System und sendet eine Test-E-Mail';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 KI-Coding E-Mail-System Test');
        $this->info('================================');

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

        $this->info("📨 Teste E-Mail-System für: {$email}");
        $this->newLine();

        // E-Mail-Konfiguration anzeigen
        $this->displayEmailConfig();

        // Test-E-Mail senden
        $this->info('📤 Sende Test-E-Mail...');

        try {
            $testData = [
                'test_type' => 'System-Test',
                'user_email' => $email,
                'server_time' => now()->toDateTimeString(),
                'app_env' => app()->environment(),
                'app_url' => config('app.url'),
                'mail_driver' => config('mail.default'),
            ];

            Mail::to($email)->send(new TestMail($testData));

            $this->info('✅ Test-E-Mail wurde erfolgreich gesendet!');
            $this->newLine();

            $this->info('🎉 Erfolgreich!');
            $this->table(['Status', 'Details'], [
                ['E-Mail-Adresse', $email],
                ['Zeitstempel', now()->format('d.m.Y H:i:s')],
                ['Mail-System', config('mail.default')],
                ['Host', config('mail.mailers.smtp.host', 'N/A')],
                ['Port', config('mail.mailers.smtp.port', 'N/A')],
                ['Absender', config('mail.from.address')],
                ['Status', '✅ Erfolgreich gesendet'],
            ]);

            $this->newLine();
            $this->info('📝 Nächste Schritte:');
            $this->line('   • Überprüfe dein E-Mail-Postfach');
            $this->line('   • Schaue auch im Spam-Ordner nach');
            $this->line('   • Teste nun die Registrierung über die Website');
            $this->line('   • Teste das Kontaktformular');

            return 0;

        } catch (Exception $e) {
            $this->error('❌ Fehler beim Senden der Test-E-Mail:');
            $this->error($e->getMessage());
            $this->newLine();

            $this->warn('🔧 Mögliche Lösungen:');
            $this->line('   • Überprüfe die SMTP-Konfiguration in der .env Datei');
            $this->line('   • Stelle sicher, dass der SMTP-Server erreichbar ist');
            $this->line('   • Überprüfe Benutzername und Passwort');
            $this->line('   • Stelle sicher, dass der Port korrekt ist');
            $this->line('   • Überprüfe, ob TLS/SSL korrekt konfiguriert ist');

            return 1;
        }
    }

    /**
     * Zeigt die aktuelle E-Mail-Konfiguration an
     */
    private function displayEmailConfig()
    {
        $this->info('⚙️  E-Mail-Konfiguration:');
        $this->table(['Einstellung', 'Wert'], [
            ['Mail-System', config('mail.default')],
            ['SMTP-Host', config('mail.mailers.smtp.host', 'Nicht konfiguriert')],
            ['SMTP-Port', config('mail.mailers.smtp.port', 'Nicht konfiguriert')],
            ['SMTP-Benutzer', config('mail.mailers.smtp.username', 'Nicht konfiguriert')],
            ['SMTP-Passwort', config('mail.mailers.smtp.password') ? '***' : 'Nicht konfiguriert'],
            ['Absender-Name', config('mail.from.name')],
            ['Absender-E-Mail', config('mail.from.address')],
        ]);
        $this->newLine();
    }
}
