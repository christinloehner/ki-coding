<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WikiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hole den Haupt-Admin aus der .env für Content-Creation
        $adminEmail = env('ADMIN_EMAIL');
        if (!$adminEmail) {
            $this->command->error('ADMIN_EMAIL ist nicht in der .env definiert!');
            return;
        }
        
        $admin = User::where('email', $adminEmail)->first();
        if (!$admin) {
            $this->command->error('Admin-User existiert nicht! Führe zuerst AdminUserSeeder aus.');
            return;
        }
        
        // Für Demo-Content verwenden wir nur den echten Admin
        $author = $admin;

        // Erstelle Kategorien
        $categories = [
            [
                'name' => 'Künstliche Intelligenz',
                'slug' => 'kuenstliche-intelligenz',
                'description' => 'Grundlagen, Techniken und Anwendungen der Künstlichen Intelligenz',
                'color' => '#6366f1',
                'icon' => 'ai',
            ],
            [
                'name' => 'Prompt Engineering',
                'slug' => 'prompt-engineering',
                'description' => 'Effektive Techniken für das Schreiben von KI-Prompts',
                'color' => '#8b5cf6',
                'icon' => 'lightbulb',
            ],
            [
                'name' => 'Machine Learning',
                'slug' => 'machine-learning',
                'description' => 'Algorithmen und Methoden des maschinellen Lernens',
                'color' => '#06b6d4',
                'icon' => 'chart',
            ],
            [
                'name' => 'Deep Learning',
                'slug' => 'deep-learning',
                'description' => 'Neuronale Netze und Deep Learning Techniken',
                'color' => '#10b981',
                'icon' => 'settings',
            ],
            [
                'name' => 'NLP',
                'slug' => 'nlp',
                'description' => 'Natural Language Processing und Sprachverarbeitung',
                'color' => '#f59e0b',
                'icon' => 'book',
            ],
            [
                'name' => 'Tools & Frameworks',
                'slug' => 'tools-frameworks',
                'description' => 'Entwicklungstools und Frameworks für KI-Projekte',
                'color' => '#ef4444',
                'icon' => 'tools',
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate([
                'slug' => $categoryData['slug']
            ], [
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'color' => $categoryData['color'],
                'icon' => $categoryData['icon'],
                'is_active' => true,
                'sort_order' => 0,
            ]);

            // Erstelle Unterkategorien für einige Hauptkategorien
            if ($categoryData['slug'] === 'kuenstliche-intelligenz') {
                Category::firstOrCreate([
                    'slug' => 'ki-grundlagen'
                ], [
                    'name' => 'KI-Grundlagen',
                    'description' => 'Grundlegende Konzepte der Künstlichen Intelligenz',
                    'color' => '#6366f1',
                    'parent_id' => $category->id,
                    'is_active' => true,
                    'sort_order' => 1,
                ]);
            }

            if ($categoryData['slug'] === 'machine-learning') {
                Category::firstOrCreate([
                    'slug' => 'supervised-learning'
                ], [
                    'name' => 'Supervised Learning',
                    'description' => 'Überwachtes Lernen mit Beispieldaten',
                    'color' => '#06b6d4',
                    'parent_id' => $category->id,
                    'is_active' => true,
                    'sort_order' => 1,
                ]);
            }
        }

        // Erstelle Tags
        $tags = [
            ['name' => 'ChatGPT', 'slug' => 'chatgpt', 'description' => 'OpenAI ChatGPT Sprachmodell', 'color' => '#10b981'],
            ['name' => 'GPT-4', 'slug' => 'gpt-4', 'description' => 'Generative Pre-trained Transformer 4', 'color' => '#6366f1'],
            ['name' => 'Claude', 'slug' => 'claude', 'description' => 'Anthropic Claude AI-Assistant', 'color' => '#8b5cf6'],
            ['name' => 'Python', 'slug' => 'python', 'description' => 'Python Programmiersprache', 'color' => '#0ea5e9'],
            ['name' => 'TensorFlow', 'slug' => 'tensorflow', 'description' => 'Google TensorFlow ML-Framework', 'color' => '#f59e0b'],
            ['name' => 'PyTorch', 'slug' => 'pytorch', 'description' => 'Meta PyTorch ML-Framework', 'color' => '#ef4444'],
            ['name' => 'Transformer', 'slug' => 'transformer', 'description' => 'Transformer-Architektur für NLP', 'color' => '#06b6d4'],
            ['name' => 'Embeddings', 'slug' => 'embeddings', 'description' => 'Vektorrepräsentationen von Daten', 'color' => '#8b5cf6'],
            ['name' => 'Fine-tuning', 'slug' => 'fine-tuning', 'description' => 'Anpassung vortrainierter Modelle', 'color' => '#10b981'],
            ['name' => 'RAG', 'slug' => 'rag', 'description' => 'Retrieval-Augmented Generation', 'color' => '#f59e0b'],
            ['name' => 'Langchain', 'slug' => 'langchain', 'description' => 'Framework für LLM-Anwendungen', 'color' => '#ef4444'],
            ['name' => 'Vector Database', 'slug' => 'vector-database', 'description' => 'Datenbanken für Vektorspeicherung', 'color' => '#06b6d4'],
        ];

        foreach ($tags as $tagData) {
            Tag::firstOrCreate([
                'slug' => $tagData['slug']
            ], [
                'name' => $tagData['name'],
                'description' => $tagData['description'],
                'color' => $tagData['color'],
                'is_featured' => false,
            ]);
        }

        // Erstelle Artikel
        $articles = [
            [
                'title' => 'Einführung in die Künstliche Intelligenz',
                'slug' => 'einfuehrung-in-die-kuenstliche-intelligenz',
                'excerpt' => 'Eine umfassende Einführung in die Grundlagen der Künstlichen Intelligenz und ihre Anwendungsbereiche.',
                'content' => $this->getArticleContent('ki-introduction'),
                'category_slug' => 'kuenstliche-intelligenz',
                'tags' => ['ChatGPT', 'GPT-4', 'Python'],
                'status' => 'published',
                'is_featured' => true,
                'author' => $admin,
                'published_at' => Carbon::now()->subDays(30),
                'views_count' => 1250,
                'reading_time' => 8,
            ],
            [
                'title' => 'Prompt Engineering: Best Practices',
                'slug' => 'prompt-engineering-best-practices',
                'excerpt' => 'Lernen Sie die wichtigsten Techniken für effektives Prompt Engineering mit Large Language Models.',
                'content' => $this->getArticleContent('prompt-engineering'),
                'category_slug' => 'prompt-engineering',
                'tags' => ['ChatGPT', 'GPT-4', 'Claude', 'Prompt Engineering'],
                'status' => 'published',
                'is_featured' => true,
                'author' => $author,
                'published_at' => Carbon::now()->subDays(15),
                'views_count' => 890,
                'reading_time' => 12,
            ],
            [
                'title' => 'Machine Learning mit Python',
                'slug' => 'machine-learning-mit-python',
                'excerpt' => 'Praktische Einführung in Machine Learning mit Python, scikit-learn und pandas.',
                'content' => $this->getArticleContent('ml-python'),
                'category_slug' => 'machine-learning',
                'tags' => ['Python', 'Machine Learning', 'scikit-learn'],
                'status' => 'published',
                'is_featured' => false,
                'author' => $author,
                'published_at' => Carbon::now()->subDays(10),
                'views_count' => 654,
                'reading_time' => 15,
            ],
            [
                'title' => 'Deep Learning mit TensorFlow',
                'slug' => 'deep-learning-mit-tensorflow',
                'excerpt' => 'Aufbau und Training neuronaler Netze mit TensorFlow 2.x.',
                'content' => $this->getArticleContent('deep-learning-tf'),
                'category_slug' => 'deep-learning',
                'tags' => ['TensorFlow', 'Deep Learning', 'Python'],
                'status' => 'published',
                'is_featured' => false,
                'author' => $admin,
                'published_at' => Carbon::now()->subDays(7),
                'views_count' => 432,
                'reading_time' => 18,
            ],
            [
                'title' => 'Natural Language Processing Grundlagen',
                'slug' => 'nlp-grundlagen',
                'excerpt' => 'Einführung in NLP-Techniken: Tokenization, Embeddings und Sprachmodelle.',
                'content' => $this->getArticleContent('nlp-basics'),
                'category_slug' => 'nlp',
                'tags' => ['NLP', 'Transformer', 'Embeddings', 'Python'],
                'status' => 'published',
                'is_featured' => false,
                'author' => $author,
                'published_at' => Carbon::now()->subDays(5),
                'views_count' => 387,
                'reading_time' => 10,
            ],
            [
                'title' => 'RAG-Systeme implementieren',
                'slug' => 'rag-systeme-implementieren',
                'excerpt' => 'Retrieval-Augmented Generation: Aufbau intelligenter Frage-Antwort-Systeme.',
                'content' => $this->getArticleContent('rag-implementation'),
                'category_slug' => 'tools-frameworks',
                'tags' => ['RAG', 'Langchain', 'Vector Database', 'Embeddings'],
                'status' => 'published',
                'is_featured' => true,
                'author' => $admin,
                'published_at' => Carbon::now()->subDays(3),
                'views_count' => 789,
                'reading_time' => 20,
            ],
            [
                'title' => 'Fine-tuning von Sprachmodellen',
                'slug' => 'fine-tuning-sprachmodelle',
                'excerpt' => 'Anpassung vortrainierter Modelle an spezifische Anwendungsfälle.',
                'content' => $this->getArticleContent('fine-tuning'),
                'category_slug' => 'deep-learning',
                'tags' => ['Fine-tuning', 'GPT-4', 'PyTorch', 'Transformer'],
                'status' => 'published',
                'is_featured' => false,
                'author' => $author,
                'published_at' => Carbon::now()->subDays(1),
                'views_count' => 156,
                'reading_time' => 14,
            ],
            [
                'title' => 'KI-Ethik und Verantwortung',
                'slug' => 'ki-ethik-und-verantwortung',
                'excerpt' => 'Ethische Aspekte beim Einsatz von Künstlicher Intelligenz.',
                'content' => $this->getArticleContent('ai-ethics'),
                'category_slug' => 'kuenstliche-intelligenz',
                'tags' => ['KI-Ethik', 'Verantwortung', 'Bias'],
                'status' => 'pending_review',
                'is_featured' => false,
                'author' => $author,
                'published_at' => null,
                'views_count' => 0,
                'reading_time' => 12,
            ],
        ];

        foreach ($articles as $articleData) {
            $category = Category::where('slug', $articleData['category_slug'])->first();
            if (!$category) continue;

            $article = Article::firstOrCreate([
                'slug' => $articleData['slug']
            ], [
                'title' => $articleData['title'],
                'excerpt' => $articleData['excerpt'],
                'content' => $articleData['content'],
                'rendered_content' => $this->renderMarkdown($articleData['content']),
                'category_id' => $category->id,
                'user_id' => $articleData['author']->id,
                'status' => $articleData['status'],
                'is_featured' => $articleData['is_featured'],
                'published_at' => $articleData['published_at'],
                'views_count' => $articleData['views_count'],
                'reading_time' => $articleData['reading_time'],
                'meta_data' => json_encode([
                    'title' => $articleData['title'] . ' - KI-Coding Wiki',
                    'description' => $articleData['excerpt'],
                    'keywords' => implode(', ', $articleData['tags']),
                ]),
            ]);

            // Tags zuweisen
            $tagIds = [];
            foreach ($articleData['tags'] as $tagName) {
                $tag = Tag::where('name', $tagName)->first();
                if ($tag) {
                    $tagIds[] = $tag->id;
                }
            }
            $article->tags()->sync($tagIds);

            // Erste Revision erstellen
            $article->createRevision(
                $articleData['author'],
                'Initial article creation'
            );

            // Kommentare für veröffentlichte Artikel
            if ($article->status === 'published') {
                $this->createCommentsForArticle($article, $admin, $author);
            }
        }

        $this->command->info('✅ Wiki-Seeder erfolgreich ausgeführt!');
        $this->command->info('📊 Erstellt:');
        $this->command->info('   - ' . Category::count() . ' Kategorien');
        $this->command->info('   - ' . Tag::count() . ' Tags');
        $this->command->info('   - ' . Article::count() . ' Artikel');
        $this->command->info('   - ' . Comment::count() . ' Kommentare');
        $this->command->info('   - ' . User::count() . ' Benutzer');
    }

    private function renderMarkdown(string $content): string
    {
        // Einfache Markdown-zu-HTML-Konvertierung für Seeds
        $html = $content;

        // Headers
        $html = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);

        // Bold und Italic
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);

        // Code blocks
        $html = preg_replace('/```(\w+)?\n(.*?)\n```/s', '<pre><code>$2</code></pre>', $html);
        $html = preg_replace('/`(.+?)`/', '<code>$1</code>', $html);

        // Listen
        $html = preg_replace('/^- (.+)$/m', '<li>$1</li>', $html);
        $html = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html);

        // Absätze
        $html = preg_replace('/\n\n/', '</p><p>', $html);
        $html = '<p>' . $html . '</p>';

        // Leere Paragraphen entfernen
        $html = preg_replace('/<p><\/p>/', '', $html);

        return $html;
    }

    private function getArticleContent(string $type): string
    {
        $contents = [
            'ki-introduction' => '# Einführung in die Künstliche Intelligenz

Künstliche Intelligenz (KI) ist eine der revolutionärsten Technologien unserer Zeit. Sie verändert die Art, wie wir arbeiten, lernen und mit Technologie interagieren.

## Was ist Künstliche Intelligenz?

Künstliche Intelligenz bezeichnet die Fähigkeit von Maschinen, Aufgaben zu lösen, die normalerweise menschliche Intelligenz erfordern. Dazu gehören:

- **Lernen**: Aus Erfahrungen und Daten lernen
- **Reasoning**: Logische Schlüsse ziehen
- **Problemlösung**: Komplexe Aufgaben bewältigen
- **Wahrnehmung**: Muster in Daten erkennen
- **Sprachverarbeitung**: Natürliche Sprache verstehen und generieren

## Arten von KI

### 1. Schwache KI (Narrow AI)
- Spezialisiert auf spezifische Aufgaben
- Beispiele: Spracherkennung, Bildklassifikation, Empfehlungssysteme

### 2. Starke KI (General AI)
- Menschenähnliche Intelligenz in allen Bereichen
- Noch nicht erreicht, aber Forschungsziel

## Anwendungsgebiete

KI findet heute in vielen Bereichen Anwendung:

- **Medizin**: Diagnose und Behandlungsempfehlungen
- **Finanzen**: Betrugserkennungund Risikobewertung
- **Transport**: Autonome Fahrzeuge
- **Bildung**: Personalisierte Lernsysteme
- **Unterhaltung**: Empfehlungsalgorithmen

## Fazit

KI ist nicht mehr nur Science-Fiction, sondern Realität. Das Verständnis der Grundlagen ist entscheidend für die Zukunft.',

            'prompt-engineering' => '# Prompt Engineering: Best Practices

Prompt Engineering ist die Kunst, effektive Anweisungen für Large Language Models zu schreiben. Es ist eine Schlüsselkompetenz im Umgang mit KI-Systemen.

## Grundprinzipien

### 1. Klarheit und Spezifität
- Seien Sie präzise in Ihren Anweisungen
- Vermeiden Sie mehrdeutige Formulierungen
- Geben Sie konkrete Beispiele

### 2. Kontext bereitstellen
- Erklären Sie den Hintergrund
- Definieren Sie die Zielgruppe
- Geben Sie relevante Informationen

### 3. Strukturierte Prompts
- Verwenden Sie klare Abschnitte
- Nutzen Sie Aufzählungen
- Arbeiten Sie mit Beispielen

## Techniken

### Few-Shot Learning
```
Beispiel:
Input: "Ich bin müde"
Output: "Emotionaler Zustand: Erschöpfung"

Input: "Das war fantastisch!"
Output: "Emotionaler Zustand: Begeisterung"

Input: "Mir ist langweilig"
Output:
```

### Chain of Thought
Lassen Sie das Modell Schritt für Schritt denken:
```
Löse das Problem Schritt für Schritt:
1. Verstehe das Problem
2. Identifiziere die wichtigsten Faktoren
3. Entwickle eine Lösung
4. Überprüfe das Ergebnis
```

### Role Playing
```
Du bist ein erfahrener Python-Entwickler.
Erkläre einem Anfänger, wie man eine Liste sortiert.
```

## Häufige Fehler

- **Zu vage Anweisungen**: "Schreibe etwas über KI"
- **Überladene Prompts**: Zu viele Anweisungen auf einmal
- **Fehlender Kontext**: Ohne Hintergrundinfos
- **Keine Beispiele**: Schwer verständliche Anforderungen

## Tipps für bessere Prompts

1. **Iterativ verbessern**: Testen und anpassen
2. **Beispiele sammeln**: Gute und schlechte Outputs dokumentieren
3. **Feedback nutzen**: Aus Fehlern lernen
4. **Experimentieren**: Verschiedene Ansätze ausprobieren

## Fazit

Gutes Prompt Engineering ist ein Handwerk, das Übung erfordert. Mit den richtigen Techniken können Sie die Leistung von KI-Systemen erheblich verbessern.',

            'ml-python' => '# Machine Learning mit Python

Python ist die führende Programmiersprache für Machine Learning. In diesem Guide lernen Sie die wichtigsten Bibliotheken und Techniken kennen.

## Warum Python für ML?

- **Einfache Syntax**: Leicht zu lernen und verwenden
- **Umfangreiche Bibliotheken**: Viele spezialisierte ML-Tools
- **Große Community**: Viel Support und Dokumentation
- **Vielseitigkeit**: Von Prototyping bis Production

## Wichtige Bibliotheken

### NumPy
Grundlage für numerische Berechnungen:
```python
import numpy as np

# Array erstellen
arr = np.array([1, 2, 3, 4, 5])
print(arr.mean())  # Durchschnitt
```

### Pandas
Datenmanipulation und -analyse:
```python
import pandas as pd

# DataFrame erstellen
df = pd.DataFrame({
    "Name": ["Alice", "Bob", "Charlie"],
    "Age": [25, 30, 35],
    "City": ["Berlin", "München", "Hamburg"]
})

print(df.head())
```

### Scikit-learn
Machine Learning Algorithmen:
```python
from sklearn.linear_model import LinearRegression
from sklearn.model_selection import train_test_split

# Model erstellen
model = LinearRegression()

# Daten aufteilen
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2)

# Model trainieren
model.fit(X_train, y_train)

# Vorhersagen
predictions = model.predict(X_test)
```

## Typischer ML-Workflow

1. **Daten sammeln**: Relevante Daten beschaffen
2. **Daten bereinigen**: Fehlende Werte, Outliers behandeln
3. **Daten explorieren**: Visualisieren und verstehen
4. **Features engineering**: Neue Features erstellen
5. **Model auswählen**: Passenden Algorithmus wählen
6. **Model trainieren**: Auf Trainingsdaten trainieren
7. **Model evaluieren**: Performance messen
8. **Model optimieren**: Hyperparameter tuning
9. **Deployment**: Model in Produktion bringen

## Beispiel: Lineare Regression

```python
import numpy as np
import matplotlib.pyplot as plt
from sklearn.linear_model import LinearRegression

# Daten generieren
X = np.array([[1], [2], [3], [4], [5]])
y = np.array([2, 4, 6, 8, 10])

# Model erstellen und trainieren
model = LinearRegression()
model.fit(X, y)

# Vorhersagen
predictions = model.predict(X)

# Visualisieren
plt.scatter(X, y, color="blue", label="Daten")
plt.plot(X, predictions, color="red", label="Vorhersagen")
plt.legend()
plt.show()
```

## Best Practices

- **Datenqualität**: Gute Daten sind wichtiger als komplexe Algorithmen
- **Kreuzvalidierung**: Immer die Generalisierbarkeit testen
- **Feature Engineering**: Oft entscheidend für gute Ergebnisse
- **Dokumentation**: Code und Experimente dokumentieren
- **Versionierung**: Daten und Modelle versionieren

## Fazit

Python bietet ein mächtiges Ökosystem für Machine Learning. Mit den richtigen Bibliotheken und Techniken können Sie komplexe ML-Projekte erfolgreich umsetzen.',

            'deep-learning-tf' => '# Deep Learning mit TensorFlow

TensorFlow ist Googles Open-Source-Framework für Deep Learning. Es ermöglicht den Aufbau und das Training komplexer neuronaler Netze.

## Installation

```bash
pip install tensorflow
```

## Grundlagen

### Tensoren
Tensoren sind die grundlegenden Datenstrukturen in TensorFlow:

```python
import tensorflow as tf

# Skalar (0D-Tensor)
scalar = tf.constant(42)

# Vektor (1D-Tensor)
vector = tf.constant([1, 2, 3, 4])

# Matrix (2D-Tensor)
matrix = tf.constant([[1, 2], [3, 4]])

# 3D-Tensor
tensor_3d = tf.constant([[[1, 2], [3, 4]], [[5, 6], [7, 8]]])
```

### Einfaches neuronales Netz

```python
import tensorflow as tf
from tensorflow.keras import layers, models

# Sequenzielles Modell
model = models.Sequential([
    layers.Dense(128, activation="relu", input_shape=(784,)),
    layers.Dropout(0.2),
    layers.Dense(10, activation="softmax")
])

# Modell kompilieren
model.compile(
    optimizer="adam",
    loss="sparse_categorical_crossentropy",
    metrics=["accuracy"]
)

# Modell-Architektur anzeigen
model.summary()
```

## Convolutional Neural Networks (CNNs)

Ideal für Bildverarbeitung:

```python
# CNN für Bildklassifikation
model = models.Sequential([
    layers.Conv2D(32, (3, 3), activation="relu", input_shape=(28, 28, 1)),
    layers.MaxPooling2D((2, 2)),
    layers.Conv2D(64, (3, 3), activation="relu"),
    layers.MaxPooling2D((2, 2)),
    layers.Conv2D(64, (3, 3), activation="relu"),
    layers.Flatten(),
    layers.Dense(64, activation="relu"),
    layers.Dense(10, activation="softmax")
])
```

## Training

```python
# Daten laden (z.B. MNIST)
(x_train, y_train), (x_test, y_test) = tf.keras.datasets.mnist.load_data()

# Daten normalisieren
x_train = x_train.astype("float32") / 255.0
x_test = x_test.astype("float32") / 255.0

# Modell trainieren
history = model.fit(
    x_train, y_train,
    epochs=5,
    batch_size=32,
    validation_data=(x_test, y_test)
)
```

## Callbacks

Nützliche Funktionen während des Trainings:

```python
from tensorflow.keras.callbacks import EarlyStopping, ModelCheckpoint

callbacks = [
    EarlyStopping(patience=3, restore_best_weights=True),
    ModelCheckpoint("best_model.h5", save_best_only=True)
]

model.fit(
    x_train, y_train,
    epochs=100,
    callbacks=callbacks,
    validation_data=(x_test, y_test)
)
```

## Transfer Learning

Vortrainierte Modelle nutzen:

```python
# Vortrainiertes Modell laden
base_model = tf.keras.applications.VGG16(
    weights="imagenet",
    include_top=False,
    input_shape=(224, 224, 3)
)

# Basis-Modell einfrieren
base_model.trainable = False

# Neues Modell erstellen
model = models.Sequential([
    base_model,
    layers.GlobalAveragePooling2D(),
    layers.Dense(128, activation="relu"),
    layers.Dense(num_classes, activation="softmax")
])
```

## Modell speichern und laden

```python
# Modell speichern
model.save("my_model.h5")

# Modell laden
loaded_model = tf.keras.models.load_model("my_model.h5")
```

## Best Practices

1. **Datenvorverarbeitung**: Normalisierung und Augmentation
2. **Batch-Größe**: Abhängig von verfügbarem Speicher
3. **Learning Rate**: Oft der wichtigste Hyperparameter
4. **Regularisierung**: Dropout, L1/L2-Regularisierung
5. **Monitoring**: Training-Verlauf überwachen

## Fazit

TensorFlow bietet ein mächtiges Framework für Deep Learning. Mit den richtigen Techniken können Sie state-of-the-art Modelle entwickeln.',

            'nlp-basics' => '# Natural Language Processing Grundlagen

Natural Language Processing (NLP) ermöglicht es Computern, menschliche Sprache zu verstehen und zu verarbeiten.

## Was ist NLP?

NLP kombiniert Computerlinguistik mit Machine Learning, um:
- Text zu verstehen und zu analysieren
- Sprache zu generieren
- Übersetzungen zu erstellen
- Sentiment-Analysen durchzuführen

## Grundlegende Konzepte

### Tokenization
Aufteilen von Text in kleinere Einheiten:

```python
import nltk
from nltk.tokenize import word_tokenize, sent_tokenize

text = "Das ist ein Beispieltext. Er hat zwei Sätze."

# Wort-Tokenization
words = word_tokenize(text)
print(words)
# ["Das", "ist", "ein", "Beispieltext", ".", "Er", "hat", "zwei", "Sätze", "."]

# Satz-Tokenization
sentences = sent_tokenize(text)
print(sentences)
# ["Das ist ein Beispieltext.", "Er hat zwei Sätze."]
```

### Preprocessing
```python
import re
from nltk.corpus import stopwords
from nltk.stem import PorterStemmer

def preprocess_text(text):
    # Kleinbuchstaben
    text = text.lower()

    # Sonderzeichen entfernen
    text = re.sub(r"[^a-zA-Z0-9\s]", "", text)

    # Tokenization
    tokens = word_tokenize(text)

    # Stopwords entfernen
    stop_words = set(stopwords.words("german"))
    tokens = [token for token in tokens if token not in stop_words]

    # Stemming
    stemmer = PorterStemmer()
    tokens = [stemmer.stem(token) for token in tokens]

    return tokens
```

## Word Embeddings

### Word2Vec
```python
from gensim.models import Word2Vec

# Training data
sentences = [["hallo", "welt"], ["python", "programmierung"]]

# Modell trainieren
model = Word2Vec(sentences, vector_size=100, window=5, min_count=1)

# Ähnliche Wörter finden
similar_words = model.wv.most_similar("python", topn=5)
```

### TF-IDF
```python
from sklearn.feature_extraction.text import TfidfVectorizer

documents = [
    "Das ist ein Beispieltext über Machine Learning",
    "Machine Learning ist ein Teilbereich der KI",
    "KI verändert die Welt"
]

vectorizer = TfidfVectorizer()
tfidf_matrix = vectorizer.fit_transform(documents)
```

## Transformer und BERT

### Hugging Face Transformers
```python
from transformers import AutoTokenizer, AutoModel

# Deutsches BERT-Modell
tokenizer = AutoTokenizer.from_pretrained("bert-base-german-cased")
model = AutoModel.from_pretrained("bert-base-german-cased")

# Text tokenisieren
text = "Das ist ein Beispieltext für BERT."
tokens = tokenizer(text, return_tensors="pt")

# Embeddings generieren
with torch.no_grad():
    outputs = model(**tokens)
    embeddings = outputs.last_hidden_state
```

## Sentiment Analysis

```python
from transformers import pipeline

# Sentiment-Pipeline
sentiment_pipeline = pipeline("sentiment-analysis",
                             model="oliverguhr/german-sentiment-bert")

# Text analysieren
text = "Ich bin sehr zufrieden mit diesem Produkt!"
result = sentiment_pipeline(text)
print(result)
# [{"label": "POSITIVE", "score": 0.9998}]
```

## Named Entity Recognition (NER)

```python
import spacy

# Deutsches spaCy-Modell laden
nlp = spacy.load("de_core_news_sm")

# Text verarbeiten
text = "Angela Merkel war Bundeskanzlerin von Deutschland."
doc = nlp(text)

# Entitäten extrahieren
for ent in doc.ents:
    print(f"{ent.text} - {ent.label_}")
# Angela Merkel - PER
# Deutschland - LOC
```

## Text Generation

```python
from transformers import GPT2LMHeadModel, GPT2Tokenizer

# Deutsches GPT-2 Modell
tokenizer = GPT2Tokenizer.from_pretrained("dbmdz/german-gpt2")
model = GPT2LMHeadModel.from_pretrained("dbmdz/german-gpt2")

# Text generieren
input_text = "Künstliche Intelligenz ist"
input_ids = tokenizer.encode(input_text, return_tensors="pt")

with torch.no_grad():
    output = model.generate(input_ids, max_length=50, num_return_sequences=1)

generated_text = tokenizer.decode(output[0], skip_special_tokens=True)
```

## Anwendungsgebiete

- **Chatbots**: Automatisierte Kundenbetreuung
- **Übersetzung**: Automatische Sprachübersetzung
- **Textanalyse**: Sentiment-Analyse von Bewertungen
- **Informationsextraktion**: Automatische Datenextraktion
- **Content-Generierung**: Automatische Texterstellung

## Fazit

NLP ist ein schnell wachsendes Feld mit enormem Potenzial. Die Kombination aus traditionellen Methoden und modernen Transformer-Architekturen eröffnet neue Möglichkeiten.',

            'rag-implementation' => '# RAG-Systeme implementieren

Retrieval-Augmented Generation (RAG) kombiniert Informationsabruf mit Textgenerierung für intelligente Frage-Antwort-Systeme.

## Was ist RAG?

RAG erweitert Large Language Models um externe Wissensdatenbanken:

1. **Retrieval**: Relevante Informationen aus einer Wissensbasis abrufen
2. **Augmentation**: Gefundene Informationen dem Prompt hinzufügen
3. **Generation**: Antwort basierend auf abgerufenen Informationen generieren

## Architektur

```
Benutzer-Frage → Embedding → Vektor-Suche → Relevante Dokumente → LLM → Antwort
```

## Implementierung mit LangChain

### Setup
```python
pip install langchain chromadb openai sentence-transformers
```

### Basis-Implementation
```python
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.embeddings import HuggingFaceEmbeddings
from langchain.vectorstores import Chroma
from langchain.llms import OpenAI
from langchain.chains import RetrievalQA

# 1. Dokumente laden und aufteilen
text_splitter = RecursiveCharacterTextSplitter(
    chunk_size=1000,
    chunk_overlap=200
)

documents = text_splitter.split_documents(raw_documents)

# 2. Embeddings erstellen
embeddings = HuggingFaceEmbeddings(
    model_name="sentence-transformers/all-MiniLM-L6-v2"
)

# 3. Vektor-Datenbank erstellen
vectorstore = Chroma.from_documents(
    documents=documents,
    embedding=embeddings,
    persist_directory="./chroma_db"
)

# 4. Retriever konfigurieren
retriever = vectorstore.as_retriever(
    search_type="similarity",
    search_kwargs={"k": 3}
)

# 5. QA-Chain erstellen
qa_chain = RetrievalQA.from_chain_type(
    llm=OpenAI(temperature=0),
    chain_type="stuff",
    retriever=retriever,
    return_source_documents=True
)
```

### Erweiterte Konfiguration
```python
from langchain.prompts import PromptTemplate

# Custom Prompt Template
prompt_template = """
Verwende die folgenden Informationen, um die Frage zu beantworten.
Wenn die Antwort nicht in den Informationen enthalten ist, sage es ehrlich.

Kontext: {context}

Frage: {question}

Antwort:
"""

PROMPT = PromptTemplate(
    template=prompt_template,
    input_variables=["context", "question"]
)

qa_chain = RetrievalQA.from_chain_type(
    llm=OpenAI(temperature=0),
    chain_type="stuff",
    retriever=retriever,
    chain_type_kwargs={"prompt": PROMPT},
    return_source_documents=True
)
```

## Vektor-Datenbanken

### Chroma
```python
import chromadb

# Client erstellen
client = chromadb.Client()

# Collection erstellen
collection = client.create_collection("my_collection")

# Dokumente hinzufügen
collection.add(
    documents=["Dokument 1", "Dokument 2"],
    metadatas=[{"source": "doc1"}, {"source": "doc2"}],
    ids=["id1", "id2"]
)

# Suche
results = collection.query(
    query_texts=["Suchbegriff"],
    n_results=2
)
```

### Pinecone
```python
import pinecone

# Pinecone initialisieren
pinecone.init(api_key="your-api-key", environment="us-east1-gcp")

# Index erstellen
index = pinecone.Index("my-index")

# Vektoren hinzufügen
index.upsert(
    vectors=[
        ("id1", [0.1, 0.2, 0.3], {"text": "Dokument 1"}),
        ("id2", [0.4, 0.5, 0.6], {"text": "Dokument 2"})
    ]
)

# Suche
results = index.query(
    vector=[0.1, 0.2, 0.3],
    top_k=2,
    include_metadata=True
)
```

## Optimierungsstrategien

### Chunk-Strategien
```python
from langchain.text_splitter import CharacterTextSplitter

# Nach Absätzen teilen
text_splitter = CharacterTextSplitter(
    separator="\n\n",
    chunk_size=1000,
    chunk_overlap=200
)

# Semantische Aufteilung
from langchain.text_splitter import SpacyTextSplitter

text_splitter = SpacyTextSplitter(
    chunk_size=1000,
    chunk_overlap=200
)
```

### Hybrid Search
```python
from langchain.retrievers import BM25Retriever, EnsembleRetriever

# BM25 (keyword-based)
bm25_retriever = BM25Retriever.from_documents(documents)

# Ensemble aus BM25 und Vektor-Suche
ensemble_retriever = EnsembleRetriever(
    retrievers=[bm25_retriever, vector_retriever],
    weights=[0.5, 0.5]
)
```

### Re-Ranking
```python
from langchain.retrievers import ContextualCompressionRetriever
from langchain.retrievers.document_compressors import LLMChainExtractor

# Kompressor für Re-Ranking
compressor = LLMChainExtractor.from_llm(llm)

# Compression Retriever
compression_retriever = ContextualCompressionRetriever(
    base_compressor=compressor,
    base_retriever=vector_retriever
)
```

## Evaluierung

### Metriken
```python
from langchain.evaluation import load_evaluator

# Relevanz-Evaluator
relevance_evaluator = load_evaluator("labeled_criteria", criteria="relevance")

# Genauigkeits-Evaluator
accuracy_evaluator = load_evaluator("exact_match")

# Evaluation
result = relevance_evaluator.evaluate_strings(
    prediction="Generierte Antwort",
    reference="Referenz-Antwort",
    input="Frage"
)
```

### A/B Testing
```python
import random

def ab_test_retriever(question, retrievers, weights):
    """Zufällige Auswahl zwischen verschiedenen Retrievern"""
    chosen_retriever = random.choices(retrievers, weights=weights)[0]
    return chosen_retriever.get_relevant_documents(question)
```

## Production-Setup

### API-Server
```python
from fastapi import FastAPI
from pydantic import BaseModel

app = FastAPI()

class Question(BaseModel):
    text: str

@app.post("/ask")
async def ask_question(question: Question):
    result = qa_chain({"query": question.text})
    return {
        "answer": result["result"],
        "sources": [doc.metadata for doc in result["source_documents"]]
    }
```

### Monitoring
```python
import logging
from langchain.callbacks import get_openai_callback

# Kosten-Tracking
with get_openai_callback() as cb:
    result = qa_chain({"query": "Beispiel-Frage"})
    print(f"Kosten: ${cb.total_cost:.4f}")
```

## Best Practices

1. **Datenqualität**: Saubere, strukturierte Dokumente
2. **Chunk-Größe**: Experimentieren mit verschiedenen Größen
3. **Embedding-Modelle**: Sprachspezifische Modelle verwenden
4. **Evaluation**: Regelmäßige Bewertung der Antwortqualität
5. **Caching**: Häufige Anfragen cachen
6. **Monitoring**: Kosten und Performance überwachen

## Fazit

RAG-Systeme ermöglichen es, aktuelle und spezifische Informationen in LLM-basierte Anwendungen einzubinden. Die richtige Implementierung erfordert sorgfältige Planung und kontinuierliche Optimierung.',

            'fine-tuning' => '# Fine-tuning von Sprachmodellen

Fine-tuning ermöglicht es, vortrainierte Modelle an spezifische Aufgaben und Domänen anzupassen.

## Was ist Fine-tuning?

Fine-tuning ist der Prozess, bei dem ein bereits trainiertes Modell auf neuen, aufgabenspezifischen Daten weitertrainiert wird:

- **Basis-Modell**: Großes, vortrainiertes Modell (z.B. BERT, GPT)
- **Ziel-Aufgabe**: Spezifische Anwendung (z.B. Sentiment-Analyse)
- **Anpassung**: Modell-Parameter für neue Aufgabe optimieren

## Vorteile

- **Weniger Daten**: Benötigt weniger Trainingsdaten als Training von Grund auf
- **Bessere Performance**: Nutzt bereits gelernte Sprachrepräsentationen
- **Effizienz**: Schnelleres Training und geringere Kosten
- **Domänen-Anpassung**: Spezialisierung auf bestimmte Bereiche

## Arten von Fine-tuning

### 1. Full Fine-tuning
Alle Parameter werden angepasst:

```python
from transformers import AutoModelForSequenceClassification, AutoTokenizer
from transformers import Trainer, TrainingArguments

# Modell laden
model = AutoModelForSequenceClassification.from_pretrained(
    "bert-base-german-cased",
    num_labels=2
)

# Tokenizer laden
tokenizer = AutoTokenizer.from_pretrained("bert-base-german-cased")

# Training-Argumente
training_args = TrainingArguments(
    output_dir="./results",
    num_train_epochs=3,
    per_device_train_batch_size=16,
    per_device_eval_batch_size=16,
    warmup_steps=500,
    weight_decay=0.01,
    logging_dir="./logs",
)

# Trainer erstellen
trainer = Trainer(
    model=model,
    args=training_args,
    train_dataset=train_dataset,
    eval_dataset=eval_dataset,
)

# Training starten
trainer.train()
```

### 2. Parameter-Efficient Fine-tuning (PEFT)

#### LoRA (Low-Rank Adaptation)
```python
from peft import LoraConfig, get_peft_model

# LoRA Konfiguration
lora_config = LoraConfig(
    r=16,                    # Rang der Anpassung
    lora_alpha=32,           # Skalierungsfaktor
    target_modules=["query", "value"],  # Ziel-Module
    lora_dropout=0.1,        # Dropout
    bias="none",             # Bias-Behandlung
    task_type="SEQ_CLS"      # Aufgabentyp
)

# PEFT-Modell erstellen
model = get_peft_model(base_model, lora_config)

# Trainierbare Parameter anzeigen
model.print_trainable_parameters()
```

#### Adapter
```python
from adapter_transformers import AdapterConfig, AdapterModel

# Adapter-Konfiguration
adapter_config = AdapterConfig.load("pfeiffer")

# Adapter hinzufügen
model.add_adapter("sentiment", config=adapter_config)

# Adapter aktivieren
model.train_adapter("sentiment")
```

### 3. Prompt Tuning
```python
from peft import PromptTuningConfig, PromptTuningInit

# Prompt Tuning Konfiguration
prompt_config = PromptTuningConfig(
    task_type="SEQ_CLS",
    prompt_tuning_init=PromptTuningInit.TEXT,
    num_virtual_tokens=20,
    prompt_tuning_init_text="Classify the sentiment of this text:",
    tokenizer_name_or_path="bert-base-german-cased",
)

# PEFT-Modell mit Prompt Tuning
model = get_peft_model(base_model, prompt_config)
```

## Datenaufbereitung

### Dataset erstellen
```python
from datasets import Dataset
import pandas as pd

# Daten laden
df = pd.read_csv("sentiment_data.csv")

# Dataset erstellen
dataset = Dataset.from_pandas(df)

# Tokenisierung
def tokenize_function(examples):
    return tokenizer(
        examples["text"],
        truncation=True,
        padding=True,
        max_length=512
    )

# Tokenisierung anwenden
tokenized_dataset = dataset.map(tokenize_function, batched=True)
```

### Datenvalidierung
```python
from sklearn.model_selection import train_test_split

# Daten aufteilen
train_texts, val_texts, train_labels, val_labels = train_test_split(
    texts, labels, test_size=0.2, random_state=42, stratify=labels
)

# Klassenverteilung prüfen
print(f"Klasse 0: {sum(train_labels == 0)} Samples")
print(f"Klasse 1: {sum(train_labels == 1)} Samples")
```

## Hyperparameter-Optimierung

### Learning Rate Scheduling
```python
from transformers import get_linear_schedule_with_warmup

# Scheduler erstellen
scheduler = get_linear_schedule_with_warmup(
    optimizer,
    num_warmup_steps=500,
    num_training_steps=total_steps
)
```

### Optuna für Hyperparameter-Suche
```python
import optuna

def objective(trial):
    # Hyperparameter vorschlagen
    learning_rate = trial.suggest_float("learning_rate", 1e-5, 5e-4, log=True)
    batch_size = trial.suggest_categorical("batch_size", [8, 16, 32])

    # Training-Argumente
    training_args = TrainingArguments(
        learning_rate=learning_rate,
        per_device_train_batch_size=batch_size,
        # ... weitere Parameter
    )

    # Trainer erstellen und trainieren
    trainer = Trainer(model=model, args=training_args, ...)
    trainer.train()

    # Evaluation
    eval_result = trainer.evaluate()
    return eval_result["eval_accuracy"]

# Studie erstellen
study = optuna.create_study(direction="maximize")
study.optimize(objective, n_trials=20)
```

## Evaluation

### Metriken
```python
from sklearn.metrics import accuracy_score, precision_recall_fscore_support

def compute_metrics(eval_pred):
    predictions, labels = eval_pred
    predictions = np.argmax(predictions, axis=1)

    accuracy = accuracy_score(labels, predictions)
    precision, recall, f1, _ = precision_recall_fscore_support(
        labels, predictions, average="weighted"
    )

    return {
        "accuracy": accuracy,
        "f1": f1,
        "precision": precision,
        "recall": recall
    }
```

### Cross-Validation
```python
from sklearn.model_selection import StratifiedKFold

kfold = StratifiedKFold(n_splits=5, shuffle=True, random_state=42)

cv_scores = []
for train_idx, val_idx in kfold.split(texts, labels):
    # Training und Evaluation für jeden Fold
    train_subset = [texts[i] for i in train_idx]
    val_subset = [texts[i] for i in val_idx]

    # ... Training und Evaluation
    cv_scores.append(eval_score)

print(f"CV Score: {np.mean(cv_scores):.4f} (+/- {np.std(cv_scores) * 2:.4f})")
```

## Deployment

### Modell speichern
```python
# Modell und Tokenizer speichern
model.save_pretrained("./fine_tuned_model")
tokenizer.save_pretrained("./fine_tuned_model")

# Für PEFT-Modelle
model.save_pretrained("./peft_model")
```

### Inferenz
```python
from transformers import pipeline

# Pipeline erstellen
classifier = pipeline(
    "sentiment-analysis",
    model="./fine_tuned_model",
    tokenizer="./fine_tuned_model"
)

# Vorhersage
result = classifier("Das ist ein großartiges Produkt!")
print(result)
```

### API-Endpoint
```python
from fastapi import FastAPI
from pydantic import BaseModel

app = FastAPI()

class TextInput(BaseModel):
    text: str

@app.post("/predict")
async def predict(input_data: TextInput):
    result = classifier(input_data.text)
    return {"prediction": result}
```

## Best Practices

1. **Datenqualität**: Saubere, repräsentative Daten
2. **Baseline**: Immer mit einfachen Modellen vergleichen
3. **Overfitting**: Regularisierung und Validation verwenden
4. **Monitoring**: Training-Verlauf überwachen
5. **Versionierung**: Modelle und Daten versionieren
6. **Documentation**: Experimente dokumentieren

## Fazit

Fine-tuning ist ein mächtiges Werkzeug zur Anpassung von Sprachmodellen. Die Wahl der richtigen Technik hängt von verfügbaren Ressourcen und Anforderungen ab.',

            'ai-ethics' => '# KI-Ethik und Verantwortung

Der Einsatz von Künstlicher Intelligenz bringt nicht nur technische Herausforderungen mit sich, sondern auch ethische Fragen, die sorgfältig betrachtet werden müssen.

## Warum KI-Ethik wichtig ist

KI-Systeme haben bereits heute erhebliche Auswirkungen auf unser Leben:
- Entscheidungen in Kreditsystemen
- Personalisierte Inhalte in sozialen Medien
- Automatisierte Bewerbungsverfahren
- Medizinische Diagnose-Unterstützung

## Ethische Prinzipien

### 1. Transparenz
KI-Systeme sollten nachvollziehbar sein:
- **Erklärbarkeit**: Wie kommt das System zu seinen Entscheidungen?
- **Dokumentation**: Welche Daten und Methoden werden verwendet?
- **Offenlegung**: Wann und wo wird KI eingesetzt?

### 2. Fairness
Vermeidung von Diskriminierung und Bias:
- **Ausgewogene Datensätze**: Repräsentative Trainingsdaten
- **Bias-Erkennung**: Systematische Überprüfung auf Vorurteile
- **Faire Behandlung**: Gleiche Chancen für alle Gruppen

### 3. Verantwortlichkeit
Klare Zuständigkeiten definieren:
- **Menschliche Aufsicht**: Menschen behalten die Kontrolle
- **Rechenschaftspflicht**: Wer ist für KI-Entscheidungen verantwortlich?
- **Korrekturmechanismen**: Möglichkeiten zur Fehlerbehebung

### 4. Datenschutz
Schutz persönlicher Informationen:
- **Datensparsamkeit**: Nur notwendige Daten sammeln
- **Zweckbindung**: Daten nur für definierte Zwecke verwenden
- **Sicherheit**: Schutz vor unbefugtem Zugriff

## Häufige Probleme

### Algorithmic Bias
```python
# Beispiel: Bias in Trainingsdaten erkennen
import pandas as pd

# Datenverteilung analysieren
df = pd.read_csv("training_data.csv")
print(df.groupby(["geschlecht", "entscheidung"]).size())

# Bias-Metriken berechnen
from sklearn.metrics import confusion_matrix
from fairlearn.metrics import equalized_odds_difference

# Fairness-Metriken
bias_score = equalized_odds_difference(
    y_true, y_pred, sensitive_features=gender
)
```

### Fehlende Diversität
Homogene Entwicklungsteams können zu einseitigen Lösungen führen:
- Verschiedene Perspektiven einbeziehen
- Diverse Teams aufbauen
- Externe Expertise hinzuziehen

### Überwachung und Kontrolle
Balance zwischen Nutzen und Überwachung:
- Datenschutz-freundliche Technologien
- Opt-in statt Opt-out
- Transparente Datennutzung

## Implementierung ethischer KI

### Ethik-Review-Prozess
```python
class EthicsReview:
    def __init__(self):
        self.checklist = [
            "Sind die Trainingsdaten repräsentativ?",
            "Wurde auf Bias getestet?",
            "Ist das System erklärbar?",
            "Gibt es Korrekturmechanismen?",
            "Sind Datenschutzbestimmungen eingehalten?"
        ]

    def review_model(self, model, data):
        results = {}
        for check in self.checklist:
            results[check] = self.perform_check(check, model, data)
        return results
```

### Bias-Mitigation
```python
from fairlearn.reductions import ExponentiatedGradient
from fairlearn.reductions import DemographicParity

# Fairness-Constraint definieren
constraint = DemographicParity()

# Bias-reduziertes Modell trainieren
mitigator = ExponentiatedGradient(base_model, constraint)
mitigator.fit(X_train, y_train, sensitive_features=sensitive_train)

# Vorhersagen mit reduziertem Bias
y_pred_fair = mitigator.predict(X_test)
```

### Explainable AI
```python
import shap

# SHAP-Erklärungen generieren
explainer = shap.TreeExplainer(model)
shap_values = explainer.shap_values(X_test)

# Visualisierung
shap.summary_plot(shap_values, X_test)
```

## Rechtliche Aspekte

### DSGVO Compliance
```python
class DataProcessor:
    def __init__(self):
        self.consent_given = False
        self.purpose_defined = False
        self.retention_period = None

    def process_data(self, data):
        if not self.consent_given:
            raise ValueError("Keine Einwilligung vorhanden")

        if not self.purpose_defined:
            raise ValueError("Verarbeitungszweck nicht definiert")

        # Datenverarbeitung...
```

### Algorithmische Entscheidungen
EU-Richtlinien für automatisierte Entscheidungen:
- Recht auf Erklärung
- Recht auf menschliche Überprüfung
- Recht auf Widerspruch

## Governance Framework

### KI-Ethik-Komitee
- Multidisziplinäres Team
- Regelmäßige Reviews
- Externe Experten einbeziehen

### Kontinuierliche Überwachung
```python
import mlflow

# Modell-Monitoring
def monitor_model_performance(model, test_data):
    predictions = model.predict(test_data)

    # Performance-Metriken
    accuracy = calculate_accuracy(predictions, test_data.labels)

    # Fairness-Metriken
    bias_score = calculate_bias(predictions, test_data.sensitive_features)

    # Logging
    mlflow.log_metric("accuracy", accuracy)
    mlflow.log_metric("bias_score", bias_score)

    # Alerts bei Problemen
    if bias_score > threshold:
        send_alert("Bias-Warnung: Modell zeigt unfaire Ergebnisse")
```

### Richtlinien und Prozesse
1. **Entwicklung**: Ethische Überlegungen von Anfang an
2. **Testing**: Umfassende Bias- und Fairness-Tests
3. **Deployment**: Monitoring und Feedback-Schleifen
4. **Wartung**: Regelmäßige Updates und Re-Evaluierung

## Stakeholder-Engagement

### Betroffene Gruppen einbeziehen
- Nutzerfeedback sammeln
- Gemeinsame Entwicklung (Co-Design)
- Kontinuierlicher Dialog

### Transparenz schaffen
- Offene Kommunikation über KI-Einsatz
- Verständliche Erklärungen
- Zugängliche Dokumentation

## Zukunft der KI-Ethik

### Entwicklungen
- Neue Regulierungen (EU AI Act)
- Verbesserte Bias-Erkennungstools
- Standardisierte Ethik-Frameworks
- Internationale Zusammenarbeit

### Herausforderungen
- Technische Komplexität
- Kulturelle Unterschiede
- Geschwindigkeit der Entwicklung
- Globale Koordination

## Fazit

KI-Ethik ist kein einmaliges Projekt, sondern ein kontinuierlicher Prozess. Verantwortungsvolle KI-Entwicklung erfordert:

- Proaktive Ethik-Überlegungen
- Diverse und inklusive Teams
- Transparente Prozesse
- Kontinuierliches Monitoring
- Offenen Dialog mit Stakeholdern

Die Investition in ethische KI zahlt sich langfristig aus - durch Vertrauen, Akzeptanz und nachhaltige Lösungen.'
        ];

        return $contents[$type] ?? 'Beispiel-Inhalt für ' . $type;
    }

    private function createCommentsForArticle(Article $article, User $admin, User $author): void
    {
        $comments = [
            [
                'content' => 'Sehr informativer Artikel! Danke für die detaillierte Erklärung.',
                'author' => $admin,
                'status' => 'approved',
                'created_at' => $article->published_at->addDays(1),
            ],
            [
                'content' => 'Könntest du mehr über praktische Anwendungen schreiben?',
                'author' => $author,
                'status' => 'approved',
                'created_at' => $article->published_at->addDays(2),
            ],
            [
                'content' => 'Ich stimme den Punkten zur Ethik vollkommen zu. Sehr wichtiges Thema!',
                'author' => $admin,
                'status' => 'approved',
                'created_at' => $article->published_at->addDays(3),
            ],
        ];

        foreach ($comments as $commentData) {
            Comment::create([
                'article_id' => $article->id,
                'user_id' => $commentData['author']->id,
                'content' => $commentData['content'],
                'status' => $commentData['status'],
                'created_at' => $commentData['created_at'],
                'updated_at' => $commentData['created_at'],
            ]);
        }
    }
}
