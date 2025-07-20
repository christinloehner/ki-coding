<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;

/**
 * Controller für die dynamische Sitemap-Generierung
 * 
 * Generiert automatisch eine XML-Sitemap mit allen öffentlichen Seiten
 * und Wiki-Artikeln für Suchmaschinen-Optimierung.
 */
class SitemapController extends Controller
{
    /**
     * Generiert und liefert die Sitemap im XML-Format
     * 
     * @return Response XML-Response mit der Sitemap
     */
    public function index(): Response
    {
        // Sitemap aus Cache laden oder generieren (Cache für 1 Stunde)
        $sitemap = Cache::remember('sitemap_cache', 3600, function () {
            // Alle veröffentlichten Artikel laden (nur die neuesten 1000 für Performance)
            $articles = Article::where('status', 'published')
                ->where('published_at', '<=', now())
                ->orderBy('updated_at', 'desc')
                ->limit(1000)
                ->get(['slug', 'updated_at', 'created_at']);

            // Alle öffentlichen Kategorien laden
            $categories = Category::whereHas('articles', function($query) {
                $query->where('status', 'published');
            })->get(['slug', 'updated_at']);

            // Alle verwendeten Tags laden
            $tags = Tag::whereHas('articles', function($query) {
                $query->where('status', 'published');
            })->get(['slug', 'updated_at']);

            // Basis-URL der Website
            $baseUrl = config('app.url');

            // Sitemap-Content generieren
            return $this->generateSitemapXml($baseUrl, $articles, $categories, $tags);
        });

        // XML-Response mit korrekten Headers zurückgeben
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600'); // 1 Stunde Cache
    }

    /**
     * Generiert den XML-Content der Sitemap
     * 
     * @param string $baseUrl Basis-URL der Website
     * @param \Illuminate\Database\Eloquent\Collection $articles Wiki-Artikel
     * @param \Illuminate\Database\Eloquent\Collection $categories Kategorien
     * @param \Illuminate\Database\Eloquent\Collection $tags Tags
     * @return string XML-Content der Sitemap
     */
    private function generateSitemapXml(string $baseUrl, $articles, $categories, $tags): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n";
        $xml .= '        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n\n";

        // Statische Seiten hinzufügen
        $xml .= $this->addStaticPages($baseUrl);

        // Wiki-Artikel hinzufügen
        $xml .= $this->addWikiArticles($baseUrl, $articles);

        // Kategorien hinzufügen
        $xml .= $this->addCategories($baseUrl, $categories);

        // Tags hinzufügen
        $xml .= $this->addTags($baseUrl, $tags);

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Fügt statische Seiten zur Sitemap hinzu
     * 
     * @param string $baseUrl Basis-URL der Website
     * @return string XML-Fragment für statische Seiten
     */
    private function addStaticPages(string $baseUrl): string
    {
        $xml = "    <!-- Statische Seiten -->\n";
        
        $staticPages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['url' => '/wiki', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => '/faq', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/about', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => '/contact', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => '/privacy', 'priority' => '0.4', 'changefreq' => 'yearly'],
            ['url' => '/imprint', 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        foreach ($staticPages as $page) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>{$baseUrl}{$page['url']}</loc>\n";
            $xml .= "        <lastmod>" . now()->toISOString() . "</lastmod>\n";
            $xml .= "        <changefreq>{$page['changefreq']}</changefreq>\n";
            $xml .= "        <priority>{$page['priority']}</priority>\n";
            $xml .= "    </url>\n\n";
        }

        return $xml;
    }

    /**
     * Fügt Wiki-Artikel zur Sitemap hinzu
     * 
     * @param string $baseUrl Basis-URL der Website
     * @param \Illuminate\Database\Eloquent\Collection $articles Wiki-Artikel
     * @return string XML-Fragment für Wiki-Artikel
     */
    private function addWikiArticles(string $baseUrl, $articles): string
    {
        if ($articles->isEmpty()) {
            return '';
        }

        $xml = "    <!-- Wiki-Artikel -->\n";

        foreach ($articles as $article) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>{$baseUrl}/wiki/article/{$article->slug}</loc>\n";
            $xml .= "        <lastmod>{$article->updated_at->toISOString()}</lastmod>\n";
            $xml .= "        <changefreq>weekly</changefreq>\n";
            $xml .= "        <priority>0.8</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= "\n";
        return $xml;
    }

    /**
     * Fügt Kategorien zur Sitemap hinzu
     * 
     * @param string $baseUrl Basis-URL der Website
     * @param \Illuminate\Database\Eloquent\Collection $categories Kategorien
     * @return string XML-Fragment für Kategorien
     */
    private function addCategories(string $baseUrl, $categories): string
    {
        if ($categories->isEmpty()) {
            return '';
        }

        $xml = "    <!-- Wiki-Kategorien -->\n";

        foreach ($categories as $category) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>{$baseUrl}/wiki/category/{$category->slug}</loc>\n";
            $xml .= "        <lastmod>{$category->updated_at->toISOString()}</lastmod>\n";
            $xml .= "        <changefreq>weekly</changefreq>\n";
            $xml .= "        <priority>0.7</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= "\n";
        return $xml;
    }

    /**
     * Fügt Tags zur Sitemap hinzu
     * 
     * @param string $baseUrl Basis-URL der Website
     * @param \Illuminate\Database\Eloquent\Collection $tags Tags
     * @return string XML-Fragment für Tags
     */
    private function addTags(string $baseUrl, $tags): string
    {
        if ($tags->isEmpty()) {
            return '';
        }

        $xml = "    <!-- Wiki-Tags -->\n";

        foreach ($tags as $tag) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>{$baseUrl}/wiki/tag/{$tag->slug}</loc>\n";
            $xml .= "        <lastmod>{$tag->updated_at->toISOString()}</lastmod>\n";
            $xml .= "        <changefreq>monthly</changefreq>\n";
            $xml .= "        <priority>0.5</priority>\n";
            $xml .= "    </url>\n";
        }

        $xml .= "\n";
        return $xml;
    }
}