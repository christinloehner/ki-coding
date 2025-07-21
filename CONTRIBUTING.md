# CONTRIBUTIONS.md

Vielen Dank für dein Interesse, zu diesem Projekt beizutragen! Wir freuen uns über jede Hilfe. Bevor du loslegst, lies bitte die folgenden Richtlinien sorgfältig durch.

## 1. Verhaltenskodex

Wir verpflichten uns, eine offene und einladende Umgebung zu schaffen. Bitte lies unseren [Verhaltenskodex](CODE_OF_CONDUCT.md) (falls vorhanden, ansonsten bitte hier einfügen oder einen Link zu einem externen Kodex), um zu verstehen, welche Verhaltensweisen erwartet und welche nicht toleriert werden.

## 2. Wie du beitragen kannst

### Fehler melden
- Überprüfe, ob der Fehler bereits gemeldet wurde.
- Beschreibe den Fehler so detailliert wie möglich: Was ist passiert? Welche Schritte führen dazu? Welche Umgebung verwendest du (Browser, OS, etc.)?
- Füge Screenshots oder Fehlermeldungen hinzu, wenn möglich.

### Verbesserungen vorschlagen
- Beschreibe deine Idee klar und prägnant.
- Erkläre, warum diese Verbesserung nützlich wäre.
- Wenn möglich, schlage eine technische Umsetzung vor.

### Code beitragen
1.  **Branching**: Wir arbeiten ausschließlich im `dev`-Branch. Bitte erstelle einen neuen Feature- oder Bugfix-Branch von `dev`.
    ```bash
    git checkout dev
    git pull origin dev
    git checkout -b feature/dein-feature-name # oder bugfix/dein-bugfix-name
    ```
2.  **Lokales Setup**:
    - Stelle sicher, dass dein lokales Entwicklungssystem auf dem neuesten Stand ist.
    - Führe `composer install` und `npm install` aus, um alle Abhängigkeiten zu installieren.
    - Setze dir eine lokale Instanz auf, wie in der README.md oder der PROJECT.md beschrieben.
3.  **Code-Qualität und Standards**:
    - Halte alle Dateien so klein wie möglich (nicht länger als 1000 Zeilen).
    - Lagere Funktionen und Methoden sinnvoll aus.
    - Füge zu jeder Funktion, Klasse und Methode Docstrings, Type Annotations und Kommentare hinzu.
    - Keine Zeile darf länger als 120 Zeichen sein.
    - Beachte IMMER die offiziellen Dokumentationen der verwendeten Tools und Frameworks.
4.  **Testen**:
    - Führe Funktionstests und Code-Quality-Tests durch.
5.  **Commit-Nachrichten**:
    - Commit-Nachrichten sollten kurz, präzise und maximal zwei Fließtext-Sätze lang sein.
    - Verwende keine Icons oder Formatierungen.
    - Beispiel: `Feat: Füge neue Benutzerregistrierung hinzu` oder `Fix: Behebe Fehler bei der Artikelanzeige`.
6.  **Merge Request erstellen**:
    - Pushe deine Änderungen in deinen Branch: `git push origin feature/dein-feature-name`
    - Erstelle einen Merge Request (MR) auf Github zum `dev`-Branch. Du darfst den Merge nicht selbst ausführen.
    ```bash
    glab mr create --source-branch dein-feature-name --target-branch dev --title "Titel deines MR" --description "Beschreibung deines MR"
    ```
    - Beschreibe im MR, was du geändert hast, warum und wie es getestet werden kann.

## 3. Datenbank-Migrationen

Wenn du Datenbank-Migrationen erstellst, stelle absolut sicher, dass Tabellen und Felder nur angelegt werden, wenn sie noch nicht existieren. Baue entsprechende Bedingungen in die Migrationen ein:
- Alle `CREATE`-Migrationen brauchen immer eine `if (!Schema::hasTable())` Prüfung!
- Alle `ADD COLUMN`-Migrationen brauchen IMMER eine `if (!Schema::hasColumn())` Prüfung.
- Die Index-Migration muss immer eine Laravel 11 kompatible `indexExists()` Methode haben!

## 4. Fragen

Wenn du Fragen hast, zögere nicht, uns zu kontaktieren.

Vielen Dank für deine Mitarbeit!
