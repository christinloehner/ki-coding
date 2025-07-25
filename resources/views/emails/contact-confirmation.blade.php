<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestätigung deiner Kontaktanfrage - KI-Coding</title>
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
        .contact-summary {
            background: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .contact-summary h3 {
            color: #374151;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
            min-width: 120px;
        }
        .detail-value {
            color: #374151;
            flex: 1;
            margin-left: 15px;
        }
        .message-box {
            background: #f0f9ff;
            border-left: 4px solid #0ea5e9;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .message-box h4 {
            margin-top: 0;
            color: #0369a1;
        }
        .message-content {
            background: white;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
            border: 1px solid #e0f2fe;
            white-space: pre-wrap;
            font-family: inherit;
        }
        .cta-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
            text-align: center;
        }
        .response-timeline {
            background: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .timeline-item {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }
        .timeline-icon {
            width: 24px;
            height: 24px;
            background: #6366f1;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 12px;
        }
        .contact-info {
            background: #f0f9ff;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .contact-info h4 {
            margin-top: 0;
            color: #0369a1;
        }
        .contact-methods {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        .contact-method {
            background: white;
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid #e0f2fe;
            text-decoration: none;
            color: #0369a1;
            font-weight: 600;
            flex: 1;
            min-width: 120px;
            text-align: center;
        }
        .contact-method:hover {
            background: #e0f2fe;
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
            <div class="subtitle">Bestätigung deiner Kontaktanfrage</div>
        </div>

        <div class="content">
            <div class="success-badge">✅ Nachricht erfolgreich gesendet!</div>

            <h2>Hallo {{ $contact->first_name }},</h2>

            <p>vielen Dank für deine Kontaktanfrage. Wir haben deine Nachricht erfolgreich erhalten und werden uns zeitnah bei dir melden.</p>

            <div class="contact-summary">
                <h3>📋 Deine Anfrage im Überblick:</h3>
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $contact->full_name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">E-Mail:</span>
                    <span class="detail-value">{{ $contact->email }}</span>
                </div>
                @if($contact->company)
                <div class="detail-item">
                    <span class="detail-label">Unternehmen:</span>
                    <span class="detail-value">{{ $contact->company }}</span>
                </div>
                @endif
                @if($contact->phone)
                <div class="detail-item">
                    <span class="detail-label">Telefon:</span>
                    <span class="detail-value">{{ $contact->phone }}</span>
                </div>
                @endif
                @if($contact->service)
                <div class="detail-item">
                    <span class="detail-label">Service-Interesse:</span>
                    <span class="detail-value">{{ $contact->service }}</span>
                </div>
                @endif
                <div class="detail-item">
                    <span class="detail-label">Eingegangen am:</span>
                    <span class="detail-value">{{ $contact->created_at->format('d.m.Y H:i') }} Uhr</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Referenz-Nr.:</span>
                    <span class="detail-value">#{{ $contact->id }}</span>
                </div>
            </div>

            <div class="message-box">
                <h4>💬 Deine Nachricht:</h4>
                <div class="message-content">{{ $contact->message }}</div>
            </div>

            <div class="response-timeline">
                <h4>⏰ Nächste Schritte:</h4>
                <div class="timeline-item">
                    <div class="timeline-icon">1</div>
                    <div>
                        <strong>Sofort:</strong> Eingangsbestätigung (diese E-Mail)
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">2</div>
                    <div>
                        <strong>Innerhalb 24h:</strong> Erste Antwort von unserem Team
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon">3</div>
                    <div>
                        <strong>Bei Bedarf:</strong> Terminvereinbarung für ein persönliches Gespräch
                    </div>
                </div>
            </div>

            <div class="cta-box">
                <h4>🚀 Dringende Anliegen?</h4>
                <p>Bei zeitkritischen Anfragen kannst du uns auch direkt kontaktieren:</p>
                <div class="contact-methods">
                    <a href="mailto:info@ki-coding.de" class="contact-method">
                        📧 E-Mail
                    </a>
                </div>
            </div>

            <div class="contact-info">
                <h4>📍 Kontaktinformationen:</h4>
                <p>
                    <strong>KI-Coding.de</strong><br>
                    c/o Christin Löhner<br>
                    Postfach 1119<br>
                    72187 Vöhringen<br>
                    Deutschland
                </p>
                <p>
                    <strong>Geschäftszeiten:</strong><br>
                    Montag - Freitag: 09:00 - 18:00 Uhr<br>
                    Samstag: 10:00 - 16:00 Uhr
                </p>
            </div>

            <div class="info-box">
                <strong>💡 Tipp:</strong> Besuche unsere <a href="{{ config('app.url') }}/wiki" style="color: #0369a1;">Knowledge Base</a> für sofortige Antworten auf häufige Fragen zu KI-Coding und unseren Services.
            </div>
        </div>

        <div class="footer">
            <p>Diese E-Mail wurde automatisch generiert. Bitte antworten Sie nicht auf diese E-Mail.</p>
            <p>
                <a href="{{ config('app.url') }}">KI-Coding.de</a> |
                <a href="{{ config('app.url') }}/wiki">Wiki</a> |
                <a href="{{ config('app.url') }}/contact">Kontakt</a> |
                <a href="{{ config('app.url') }}/privacy">Datenschutz</a>
            </p>
            <p style="margin-top: 15px; color: #9ca3af;">
                © {{ date('Y') }} KI-Coding GmbH. Alle Rechte vorbehalten.
            </p>
        </div>
    </div>
</body>
</html>
