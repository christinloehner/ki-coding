<?php

namespace App\Services;

/**
 * Service für das Vergleichen von Text-Inhalten zwischen verschiedenen Artikel-Revisionen.
 * 
 * Generiert HTML-Diff-Ausgaben ähnlich wie MediaWiki/Wikipedia mit eingefärbten
 * Hinzufügungen (grün) und Löschungen (rot).
 */
class DiffService
{
    /**
     * Vergleiche zwei Texte und generiere ein HTML-Diff.
     * 
     * @param string $oldText Der ältere Text
     * @param string $newText Der neuere Text
     * @param string $type Der Typ des Vergleichs ('title', 'content', 'excerpt')
     * @return array Array mit 'old' und 'new' HTML-Segmenten
     */
    public function generateDiff(string $oldText, string $newText, string $type = 'content'): array
    {
        // Normalisiere die Texte
        $oldText = $this->normalizeText($oldText);
        $newText = $this->normalizeText($newText);
        
        if ($type === 'content') {
            return $this->generateLineDiff($oldText, $newText);
        } else {
            return $this->generateWordDiff($oldText, $newText);
        }
    }
    
    /**
     * Generiere ein zeilenbasiertes Diff für längere Inhalte.
     * 
     * @param string $oldText
     * @param string $newText
     * @return array
     */
    private function generateLineDiff(string $oldText, string $newText): array
    {
        $oldLines = explode("\n", $oldText);
        $newLines = explode("\n", $newText);
        
        $diff = $this->computeDiff($oldLines, $newLines);
        
        $oldHtml = [];
        $newHtml = [];
        
        foreach ($diff as $operation) {
            switch ($operation['type']) {
                case 'equal':
                    $oldHtml[] = '<div class="diff-line diff-equal">' . htmlspecialchars($operation['old']) . '</div>';
                    $newHtml[] = '<div class="diff-line diff-equal">' . htmlspecialchars($operation['new']) . '</div>';
                    break;
                    
                case 'delete':
                    $oldHtml[] = '<div class="diff-line diff-deleted">' . htmlspecialchars($operation['old']) . '</div>';
                    break;
                    
                case 'insert':
                    $newHtml[] = '<div class="diff-line diff-added">' . htmlspecialchars($operation['new']) . '</div>';
                    break;
                    
                case 'replace':
                    $oldHtml[] = '<div class="diff-line diff-deleted">' . htmlspecialchars($operation['old']) . '</div>';
                    $newHtml[] = '<div class="diff-line diff-added">' . htmlspecialchars($operation['new']) . '</div>';
                    break;
            }
        }
        
        return [
            'old' => implode('', $oldHtml),
            'new' => implode('', $newHtml)
        ];
    }
    
    /**
     * Generiere ein wortbasiertes Diff für kürzere Texte wie Titel.
     * 
     * @param string $oldText
     * @param string $newText
     * @return array
     */
    private function generateWordDiff(string $oldText, string $newText): array
    {
        if ($oldText === $newText) {
            return [
                'old' => '<span class="diff-equal">' . htmlspecialchars($oldText) . '</span>',
                'new' => '<span class="diff-equal">' . htmlspecialchars($newText) . '</span>'
            ];
        }
        
        $oldWords = preg_split('/(\s+)/', $oldText, -1, PREG_SPLIT_DELIM_CAPTURE);
        $newWords = preg_split('/(\s+)/', $newText, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        $diff = $this->computeDiff($oldWords, $newWords);
        
        $oldHtml = '';
        $newHtml = '';
        
        foreach ($diff as $operation) {
            switch ($operation['type']) {
                case 'equal':
                    $oldHtml .= '<span class="diff-equal">' . htmlspecialchars($operation['old']) . '</span>';
                    $newHtml .= '<span class="diff-equal">' . htmlspecialchars($operation['new']) . '</span>';
                    break;
                    
                case 'delete':
                    $oldHtml .= '<span class="diff-deleted">' . htmlspecialchars($operation['old']) . '</span>';
                    break;
                    
                case 'insert':
                    $newHtml .= '<span class="diff-added">' . htmlspecialchars($operation['new']) . '</span>';
                    break;
                    
                case 'replace':
                    $oldHtml .= '<span class="diff-deleted">' . htmlspecialchars($operation['old']) . '</span>';
                    $newHtml .= '<span class="diff-added">' . htmlspecialchars($operation['new']) . '</span>';
                    break;
            }
        }
        
        return [
            'old' => $oldHtml,
            'new' => $newHtml
        ];
    }
    
    /**
     * Berechne das Diff zwischen zwei Arrays (Myers-Algorithmus vereinfacht).
     * 
     * @param array $old
     * @param array $new
     * @return array
     */
    private function computeDiff(array $old, array $new): array
    {
        $matrix = [];
        $oldCount = count($old);
        $newCount = count($new);
        
        // Initialisiere die Matrix
        for ($i = 0; $i <= $oldCount; $i++) {
            $matrix[$i][0] = $i;
        }
        for ($j = 0; $j <= $newCount; $j++) {
            $matrix[0][$j] = $j;
        }
        
        // Fülle die Matrix
        for ($i = 1; $i <= $oldCount; $i++) {
            for ($j = 1; $j <= $newCount; $j++) {
                if ($old[$i-1] === $new[$j-1]) {
                    $matrix[$i][$j] = $matrix[$i-1][$j-1];
                } else {
                    $matrix[$i][$j] = min(
                        $matrix[$i-1][$j] + 1,     // Löschung
                        $matrix[$i][$j-1] + 1,     // Einfügung
                        $matrix[$i-1][$j-1] + 1    // Ersetzung
                    );
                }
            }
        }
        
        // Rekonstruiere den Pfad
        $operations = [];
        $i = $oldCount;
        $j = $newCount;
        
        while ($i > 0 || $j > 0) {
            if ($i > 0 && $j > 0 && $old[$i-1] === $new[$j-1]) {
                $operations[] = [
                    'type' => 'equal',
                    'old' => $old[$i-1],
                    'new' => $new[$j-1]
                ];
                $i--;
                $j--;
            } elseif ($j > 0 && ($i === 0 || $matrix[$i][$j-1] <= $matrix[$i-1][$j])) {
                $operations[] = [
                    'type' => 'insert',
                    'new' => $new[$j-1]
                ];
                $j--;
            } elseif ($i > 0) {
                $operations[] = [
                    'type' => 'delete',
                    'old' => $old[$i-1]
                ];
                $i--;
            }
        }
        
        return array_reverse($operations);
    }
    
    /**
     * Normalisiere Text für den Vergleich.
     * 
     * @param string $text
     * @return string
     */
    private function normalizeText(string $text): string
    {
        // Entferne überschüssige Leerzeichen und normalisiere Zeilenendezeichen
        $text = preg_replace('/\r\n|\r/', "\n", $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = trim($text);
        
        return $text;
    }
    
    /**
     * Generiere Statistiken für ein Diff.
     * 
     * @param string $oldText
     * @param string $newText
     * @return array
     */
    public function getDiffStats(string $oldText, string $newText): array
    {
        $oldLines = explode("\n", $this->normalizeText($oldText));
        $newLines = explode("\n", $this->normalizeText($newText));
        
        $diff = $this->computeDiff($oldLines, $newLines);
        
        $stats = [
            'lines_added' => 0,
            'lines_removed' => 0,
            'lines_changed' => 0,
            'lines_unchanged' => 0
        ];
        
        foreach ($diff as $operation) {
            switch ($operation['type']) {
                case 'equal':
                    $stats['lines_unchanged']++;
                    break;
                case 'delete':
                    $stats['lines_removed']++;
                    break;
                case 'insert':
                    $stats['lines_added']++;
                    break;
                case 'replace':
                    $stats['lines_changed']++;
                    break;
            }
        }
        
        return $stats;
    }
}