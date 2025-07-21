<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // KI-Coding Kategoriestruktur erstellen
        $this->createCategoryStructure();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Diese Migration kann nicht automatisch rückgängig gemacht werden,
        // da sie bestehende Kategorien erhält und nur neue hinzufügt
    }

    /**
     * Erstellt die komplette KI-Coding Kategoriestruktur
     */
    private function createCategoryStructure(): void
    {
        // Prüfen ob categories Tabelle existiert
        if (!Schema::hasTable('categories')) {
            throw new \Exception('Categories Tabelle existiert nicht. Führe zuerst create_categories_table Migration aus.');
        }
        $categories = [
            // Hauptkategorie 1: KI‑Coding & Vibe‑Coding
            [
                'name' => 'KI‑Coding & Vibe‑Coding',
                'description' => 'Begrüßt Neulinge bei den Grundlagen dieser Programmierphilosophie: was Vibe‑Coding ist, wie es sich von klassischem Prompt‑Engineering unterscheidet und wie man gezielt in den Flow kommt.',
                'children' => [
                    [
                        'name' => 'Einführung',
                        'description' => 'Grundlagen, warum Vibe‑Coding anders ist – Einstieg für Beginner.',
                    ],
                    [
                        'name' => 'Workflow & Denkweise',
                        'description' => 'Tipps für Flow, Iteration und zielführendes Arbeiten mit KI im Development‑Flow.',
                    ],
                ]
            ],

            // Hauptkategorie 2: Tooling & Umgebung
            [
                'name' => 'Tooling & Umgebung',
                'description' => 'Beschreibt die grundlegende Tech‑Ausstattung fürs KI‑Coding – von LLM‑Modellen wie GPT‑4 und Claude bis hin zu Editoren und Plugins, die deinen Coding‑Workflow unterstützen.',
                'children' => [
                    [
                        'name' => 'LLM‑Art & Nutzung',
                        'description' => 'Vorstellung verschiedener Modelle (GPT‑4, Claude…) und Tipps zur Auswahl.',
                    ],
                    [
                        'name' => 'Editoren & Plugins',
                        'description' => 'Tools wie Cursor, VS Code, Replit & Co. – Einrichtungstipps und Plugins im Überblick.',
                    ],
                ]
            ],

            // Hauptkategorie 3: Prompts & Patterns
            [
                'name' => 'Prompts & Patterns',
                'description' => 'Führt systematisch in effektive Prompt‑Strukturen ein: von Basics wie Zero‑ und Few‑Shot bis zu bewährten Patterns (z. B. Chain‑of‑Thought), die wiederverwendbare Templates möglich machen.',
                'children' => [
                    [
                        'name' => 'Prompt‑Grundlagen',
                        'description' => 'System-, Zero‑Shot‑ und Few‑Shot‑Prompts – verständlich erklärt.',
                    ],
                    [
                        'name' => 'Module & Templates',
                        'description' => 'Wiederverwendbare Prompt‑Bausteine für häufige Aufgaben.',
                    ],
                    [
                        'name' => 'Prompt‑Strategien',
                        'description' => 'Erweiterte Patterns wie Chain‑of‑Thought oder Self‑Consistency für effektivere Prompts.',
                    ],
                ]
            ],

            // Hauptkategorie 4: Tutorials & Praxis‑Projekte
            [
                'name' => 'Tutorials & Praxis‑Projekte',
                'description' => 'Sorgt für handfeste Ergebnisse: mit leicht nachvollziehbaren Schritt‑für‑Schritt‑Projekten – vom „Hello World" über Web‑Apps bis hin zu Chatbots und Deployment.',
                'children' => [
                    [
                        'name' => 'Hello World Projekte',
                        'description' => 'Erste Mini‑Projekte, um mit KI‑Coding durchzustarten.',
                    ],
                    [
                        'name' => 'Web‑Apps & Bots',
                        'description' => 'Schritt‑für‑Schritt zu funktionierenden Web‑Apps oder Chat‑Bots.',
                    ],
                    [
                        'name' => 'Deployment',
                        'description' => 'Hochladen, automatisiertes Bauen, Hosting und Live‑Betrieb erklären.',
                    ],
                ]
            ],

            // Hauptkategorie 5: Qualität & Refactoring
            [
                'name' => 'Qualität & Refactoring',
                'description' => 'Zeigt, wie man KI‑generierten Code prüft, debuggt und sauber strukturiert wiederverwendbar macht – fokussiert auf Tests, Reviews und Refactoring.',
                'children' => [
                    [
                        'name' => 'Code‑Review mit KI',
                        'description' => 'Wie du generierten Code analysierst, testest und verfeinerst.',
                    ],
                    [
                        'name' => 'Debugging',
                        'description' => 'Praxistipps, wie man Fehler in KI‑Code findet und ausbügelt.',
                    ],
                    [
                        'name' => 'Refactoring Basics',
                        'description' => 'Code sauber strukturieren und wartbar machen – auch nach dem Flow‑Prototyp.',
                    ],
                ]
            ],

            // Hauptkategorie 6: Git & Zusammenarbeit
            [
                'name' => 'Git & Zusammenarbeit',
                'description' => 'Behandelt alle Essentials zu Versionskontrolle im KI‑Zeitalter: von Git‑Grundlagen über Branch‑Strategien bis hin zum Workflow im Team.',
                'children' => [
                    [
                        'name' => 'Versionskontrolle',
                        'description' => 'Git‑Grundlagen, Commits verstehen und effektive History‑Führung.',
                    ],
                    [
                        'name' => 'Team‑Workflows',
                        'description' => 'Branch‑Strategien, Reviews & Merge‑Techniken im Team‑Kontext.',
                    ],
                ]
            ],

            // Hauptkategorie 7: Sicherheit & Recht
            [
                'name' => 'Sicherheit & Recht',
                'description' => 'Markiert bewährte Praktiken rund ums Thema Sicherheit, Lizenzfragen und Ethik – damit dein KI‑Coding rechtlich sauber und verantwortungsvoll bleibt.',
                'children' => [
                    [
                        'name' => 'Code‑Security',
                        'description' => 'Einfache Sicherheitschecks, Schutz vor Risiken im generierten Code.',
                    ],
                    [
                        'name' => 'Lizenzen & Copyright',
                        'description' => 'Tipps zum rechtssicheren Umgang mit KI‑Code, Lizenzierung beachten.',
                    ],
                    [
                        'name' => 'Ethik & Bias',
                        'description' => 'Wie man verantwortungsvoll mit KI umgeht und Bias vermeidet.',
                    ],
                ]
            ],

            // Hauptkategorie 8: Fortgeschrittene Techniken
            [
                'name' => 'Fortgeschrittene Techniken',
                'description' => 'Gibt expliziten Tiefgang: für den Umgang mit AI‑Agenten, Kontextmanagement in großen Projekten und Strategien zum Skalieren bei komplexen Anwendungen.',
                'children' => [
                    [
                        'name' => 'Agenten & Automatisierung',
                        'description' => 'Einführung in AI‑Agents, automatisierte Workflows (z. B. LangChain).',
                    ],
                    [
                        'name' => 'Kontextmanagement',
                        'description' => 'Chunking, Kontextfenster, damit große Projekte strukturiert bleiben.',
                    ],
                    [
                        'name' => 'Skalierung & große Projekte',
                        'description' => 'Tipps für größere Anwendungen, Modularisierung und Struktur.',
                    ],
                ]
            ],

            // Hauptkategorie 9: Grundlagen der KI & LLM‑Theorie
            [
                'name' => 'Grundlagen der KI & LLM‑Theorie',
                'description' => 'Vermittelt kompakt das nötige Hintergrundwissen über KI, maschinelles Lernen, Deep Learning und NLP – damit Einsteiger und Fortgeschrittene verstehen, wie LLMs wirklich funktionieren.',
                'children' => [
                    [
                        'name' => 'Überblick KI/DL/ML/NLP',
                        'description' => 'Einsteigerfreundliche Erklärungen, was hinter KI steckt.',
                    ],
                    [
                        'name' => 'Wie LLMs funktionieren',
                        'description' => 'Modelle, Training, Stärken und Grenzen kompakt dargestellt.',
                    ],
                ]
            ],

            // Hauptkategorie 10: Ressourcen & Glossar
            [
                'name' => 'Ressourcen & Glossar',
                'description' => 'Der zentrale Sammelplatz für wichtige Begriffe, weiterführende Links, FAQs und Checklisten – für schnelles Nachschlagen und Wissenserweiterung.',
                'children' => [
                    [
                        'name' => 'Glossar',
                        'description' => 'Kurzdefinitionen aller wichtigen Begriffe rund um KI‑Coding.',
                    ],
                    [
                        'name' => 'Weiterführende Quellen',
                        'description' => 'Links zu Artikeln, Tools, Videos, Kursen und Communities.',
                    ],
                    [
                        'name' => 'FAQ & Troubleshooting',
                        'description' => 'Antworten auf häufige Fragen und Tipps bei typischen Problemen.',
                    ],
                ]
            ],
        ];

        // Kategorien erstellen
        foreach ($categories as $mainCategory) {
            $this->createCategoryWithChildren($mainCategory);
        }
    }

    /**
     * Erstellt eine Hauptkategorie mit ihren Unterkategorien
     */
    private function createCategoryWithChildren(array $categoryData): void
    {
        // Generiere Slug aus Name
        $slug = $this->generateSlug($categoryData['name']);
        
        // Prüfen ob Hauptkategorie bereits existiert
        $existingCategory = DB::table('categories')
            ->where('name', $categoryData['name'])
            ->orWhere('slug', $slug)
            ->first();

        if ($existingCategory) {
            // Kategorie existiert bereits, verwende die bestehende ID
            $parentId = $existingCategory->id;
        } else {
            // Hauptkategorie erstellen
            $parentId = DB::table('categories')->insertGetId([
                'name' => $categoryData['name'],
                'slug' => $slug,
                'description' => $categoryData['description'],
                'parent_id' => null,
                'color' => '#6366f1', // Standard-Farbe
                'icon' => 'folder',
                'sort_order' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Unterkategorien erstellen (falls vorhanden)
        if (isset($categoryData['children'])) {
            foreach ($categoryData['children'] as $childCategory) {
                $childSlug = $this->generateSlug($childCategory['name']);
                
                // Prüfen ob Unterkategorie bereits existiert
                $existingChild = DB::table('categories')
                    ->where('name', $childCategory['name'])
                    ->orWhere('slug', $childSlug)
                    ->first();

                if (!$existingChild) {
                    // Unterkategorie erstellen
                    DB::table('categories')->insert([
                        'name' => $childCategory['name'],
                        'slug' => $childSlug,
                        'description' => $childCategory['description'],
                        'parent_id' => $parentId,
                        'color' => '#6366f1', // Standard-Farbe
                        'icon' => 'folder',
                        'sort_order' => 0,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Generiert einen URL-freundlichen Slug aus einem Namen
     */
    private function generateSlug(string $name): string
    {
        // Ersetze Sonderzeichen und erstelle einen sauberen Slug
        $slug = $name;
        
        // Deutsche Umlaute und Sonderzeichen ersetzen
        $slug = str_replace(['ä', 'ö', 'ü', 'ß', 'Ä', 'Ö', 'Ü'], ['ae', 'oe', 'ue', 'ss', 'Ae', 'Oe', 'Ue'], $slug);
        
        // Sonderzeichen ersetzen
        $slug = str_replace(['‑', '–', '—'], '-', $slug); // Verschiedene Bindestrich-Arten
        $slug = str_replace(['&'], 'und', $slug);
        
        // Standard Laravel Slug-Generierung
        $slug = Str::slug($slug);
        
        // Eindeutigkeit sicherstellen
        $originalSlug = $slug;
        $counter = 1;
        
        while (DB::table('categories')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
};