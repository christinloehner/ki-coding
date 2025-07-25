<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KI-Coding Test-Email</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9fafb;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #6366f1;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #6b7280;
            font-size: 14px;
        }
        .content {
            margin-bottom: 30px;
        }
        .success-badge {
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .test-details {
            background: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .test-details h3 {
            color: #374151;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .test-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .test-item:last-child {
            border-bottom: none;
        }
        .test-label {
            font-weight: 600;
            color: #6b7280;
        }
        .test-value {
            color: #374151;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        .footer a {
            color: #6366f1;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">KI-Coding</div>
            <div class="subtitle">E-Mail-System Test</div>
        </div>

        <div class="content">
            <div class="success-badge">✅ E-Mail-System funktioniert!</div>

            <h2>Test-E-Mail erfolgreich versendet</h2>

            <div class="info-box">
                <strong>🎉 Gratulation!</strong> Wenn du diese E-Mail erhalten hast, funktioniert dein E-Mail-System korrekt.
            </div>

            <div class="test-details">
                <h3>Test-Details:</h3>
                <div class="test-item">
                    <span class="test-label">Zeitstempel:</span>
                    <span class="test-value">{{ $timestamp }}</span>
                </div>
                <div class="test-item">
                    <span class="test-label">Mail-System:</span>
                    <span class="test-value">{{ config('mail.default') }}</span>
                </div>
                <div class="test-item">
                    <span class="test-label">Host:</span>
                    <span class="test-value">{{ config('mail.mailers.smtp.host') }}</span>
                </div>
                <div class="test-item">
                    <span class="test-label">Port:</span>
                    <span class="test-value">{{ config('mail.mailers.smtp.port') }}</span>
                </div>
                <div class="test-item">
                    <span class="test-label">Absender:</span>
                    <span class="test-value">{{ config('mail.from.address') }}</span>
                </div>
                <div class="test-item">
                    <span class="test-label">Laravel Version:</span>
                    <span class="test-value">{{ app()->version() }}</span>
                </div>
                <div class="test-item">
                    <span class="test-label">PHP Version:</span>
                    <span class="test-value">{{ PHP_VERSION }}</span>
                </div>
            </div>

            @if($testData)
                <div class="test-details">
                    <h3>Zusätzliche Test-Daten:</h3>
                    @foreach($testData as $key => $value)
                        <div class="test-item">
                            <span class="test-label">{{ $key }}:</span>
                            <span class="test-value">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="info-box">
                <strong>Nächste Schritte:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Registrierungs-E-Mails sind nun funktionsfähig</li>
                    <li>Kontaktformular-E-Mails werden versendet</li>
                    <li>E-Mail-Verifizierung funktioniert</li>
                    <li>Passwort-Reset-E-Mails werden gesendet</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Diese E-Mail wurde automatisch vom KI-Coding Wiki-System gesendet.</p>
            <p>
                <a href="{{ config('app.url') }}">KI-Coding.de</a> |
                <a href="{{ config('app.url') }}/wiki">Wiki</a> |
                <a href="{{ config('app.url') }}/contact">Kontakt</a>
            </p>
        </div>
    </div>
</body>
</html>
