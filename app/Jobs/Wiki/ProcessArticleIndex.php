<?php

namespace App\Jobs\Wiki;

use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Job für die Indizierung von Artikeln für die Suche
 */
class ProcessArticleIndex implements ShouldQueue
{
    use Queueable;

    /**
     * Anzahl der Wiederholungsversuche
     */
    public int $tries = 3;

    /**
     * Timeout in Sekunden
     */
    public int $timeout = 60;

    /**
     * Artikel der indiziert werden soll
     */
    public Article $article;

    /**
     * Create a new job instance.
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
        $this->onQueue('wiki-indexing');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting article indexing', ['article_id' => $this->article->id]);

            // Prüfen ob Artikel indiziert werden soll
            if ($this->article->shouldBeSearchable()) {
                // Artikel zur Suchindizierung hinzufügen
                $this->article->searchable();
                
                Log::info('Article indexed successfully', ['article_id' => $this->article->id]);
            } else {
                // Artikel aus Suchindex entfernen
                $this->article->unsearchable();
                
                Log::info('Article removed from index', ['article_id' => $this->article->id]);
            }

            // Zusätzliche Metadaten aktualisieren
            $this->updateSearchMetadata();

        } catch (\Exception $e) {
            Log::error('Article indexing failed', [
                'article_id' => $this->article->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Aktualisiert zusätzliche Suchmetadaten
     */
    private function updateSearchMetadata(): void
    {
        // Artikel-Statistiken aktualisieren
        $this->article->update([
            'search_indexed_at' => now(),
        ]);

        // Verwandte Artikel-Cache invalidieren
        cache()->forget("related_articles_{$this->article->id}");
        
        // Kategorie-Cache invalidieren
        if ($this->article->category) {
            cache()->forget("category_articles_{$this->article->category->id}");
        }

        // Tag-Cache invalidieren
        foreach ($this->article->tags as $tag) {
            cache()->forget("tag_articles_{$tag->id}");
        }
    }

    /**
     * Behandlung wenn der Job fehlschlägt
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Article indexing job failed permanently', [
            'article_id' => $this->article->id,
            'error' => $exception->getMessage()
        ]);
    }
}
