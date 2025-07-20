<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neuer Artikel veröffentlicht</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .article-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .article-meta {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .article-excerpt {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 20px;
            color: #495057;
        }
        .cta-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .cta-button:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .tags {
            margin-top: 15px;
        }
        .tag {
            display: inline-block;
            background-color: #e9ecef;
            color: #495057;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 KI-Coding Wiki</h1>
        <p>Ein neuer Artikel wurde veröffentlicht!</p>
    </div>

    <div class="content">
        <div class="article-title">{{ $article->title }}</div>
        
        <div class="article-meta">
            <strong>Autor:</strong> {{ $author->name }}<br>
            <strong>Kategorie:</strong> {{ $category->name }}<br>
            <strong>Veröffentlicht:</strong> {{ $article->published_at->format('d.m.Y H:i') }} Uhr<br>
            <strong>Lesezeit:</strong> {{ $article->reading_time }} Minuten
        </div>

        @if($article->excerpt)
        <div class="article-excerpt">
            {{ $article->excerpt }}
        </div>
        @endif

        <a href="{{ $articleUrl }}" class="cta-button">Artikel lesen</a>

        @if($tags->count() > 0)
        <div class="tags">
            <strong>Tags:</strong><br>
            @foreach($tags as $tag)
                <span class="tag">{{ $tag->name }}</span>
            @endforeach
        </div>
        @endif
    </div>

    <div class="footer">
        <p>
            Du erhältst diese E-Mail, weil du Benachrichtigungen über neue Artikel aktiviert hast.<br>
            <a href="{{ $unsubscribeUrl }}">Benachrichtigungen verwalten</a>
        </p>
        
        <p>
            Mit freundlichen Grüßen,<br>
            Das KI-Coding Team
        </p>
    </div>
</body>
</html>