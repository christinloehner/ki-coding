<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neue Kontaktanfrage - KI-Coding</title>
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
        .alert {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .contact-details {
            background: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .contact-details h3 {
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
        .actions {
            background: #f8fafc;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background: #6366f1;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 5px;
        }
        .btn:hover {
            background: #4f46e5;
        }
        .btn-outline {
            background: white;
            color: #6366f1;
            border: 2px solid #6366f1;
        }
        .btn-outline:hover {
            background: #6366f1;
            color: white;
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
            <div class="subtitle">Neue Kontaktanfrage</div>
        </div>

        <div class="content">
            <div class="alert">
                <strong>📧 Neue Kontaktanfrage eingegangen!</strong><br>
                Eingegangen am {{ $contact->created_at->format('d.m.Y') }} um {{ $contact->created_at->format('H:i') }} Uhr
            </div>

            <div class="contact-details">
                <h3>Kontaktdaten:</h3>
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $contact->full_name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">E-Mail:</span>
                    <span class="detail-value">
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                    </span>
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
                    <span class="detail-value">
                        <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                    </span>
                </div>
                @endif
                @if($contact->service)
                <div class="detail-item">
                    <span class="detail-label">Service-Interesse:</span>
                    <span class="detail-value">{{ $contact->service }}</span>
                </div>
                @endif
                <div class="detail-item">
                    <span class="detail-label">Kontakt-ID:</span>
                    <span class="detail-value">#{{ $contact->id }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">{{ ucfirst($contact->status) }}</span>
                </div>
            </div>

            <div class="message-box">
                <h4>💬 Nachricht:</h4>
                <div class="message-content">{{ $contact->message }}</div>
            </div>

            <div class="actions">
                <h4>Schnellaktionen:</h4>
                <a href="mailto:{{ $contact->email }}?subject=Re: Deine Kontaktanfrage - KI-Coding&body=Hallo {{ $contact->first_name }},%0D%0A%0D%0Avielen Dank für deine Kontaktanfrage.%0D%0A%0D%0AMit freundlichen Grüßen%0D%0ADein KI-Coding Team" class="btn">
                    📧 Direkt antworten
                </a>
                @if($contact->phone)
                <a href="tel:{{ $contact->phone }}" class="btn btn-outline">
                    📞 Anrufen
                </a>
                @endif
            </div>
        </div>

        <div class="footer">
            <p>Diese E-Mail wurde automatisch vom KI-Coding Kontaktformular gesendet.</p>
            <p>
                <a href="{{ config('app.url') }}/admin">Admin-Panel</a> |
                <a href="{{ config('app.url') }}/contact">Kontaktformular</a>
            </p>
        </div>
    </div>
</body>
</html>
