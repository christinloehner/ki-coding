<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class WikiSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test Wiki-Index-Seite
     */
    public function test_wiki_index_accessible(): void
    {
        $response = $this->get('/wiki');
        $response->assertStatus(200);
    }

    /**
     * Test Artikel-Erstellung mit Authentifizierung
     */
    public function test_authenticated_user_can_create_article(): void
    {
        // Rollen erstellen
        $contributorRole = Role::create(['name' => 'contributor']);
        
        // Benutzer erstellen
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $user->assignRole('contributor');
        
        // Kategorie erstellen
        $category = Category::factory()->create();
        
        // Als Benutzer einloggen
        $this->actingAs($user);
        
        // Artikel erstellen
        $articleData = [
            'title' => 'Test Artikel',
            'content' => 'Dies ist ein Test-Artikel mit **Markdown** Inhalt.',
            'category_id' => $category->id,
            'status' => 'published',
            'allow_comments' => true,
        ];
        
        $response = $this->post('/wiki/articles', $articleData);
        
        // Prüfen ob Artikel erstellt wurde
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Artikel',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Test Artikel-Anzeige
     */
    public function test_published_article_is_viewable(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $article = Article::factory()->create([
            'title' => 'Sichtbarer Artikel',
            'slug' => 'sichtbarer-artikel',
            'content' => 'Dies ist ein sichtbarer Artikel.',
            'status' => 'published',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'published_at' => now(),
        ]);
        
        $response = $this->get('/wiki/article/' . $article->slug);
        
        $response->assertStatus(200);
        $response->assertSee('Sichtbarer Artikel');
    }

    /**
     * Test Suche funktioniert
     */
    public function test_search_functionality(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        Article::factory()->create([
            'title' => 'Laravel Tutorial',
            'content' => 'Ein umfassendes Laravel Tutorial.',
            'status' => 'published',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        
        $response = $this->get('/wiki/search?q=Laravel');
        
        $response->assertStatus(200);
        $response->assertSee('Laravel Tutorial');
    }

    /**
     * Test unauthentifizierte Benutzer können keine Artikel erstellen
     */
    public function test_unauthenticated_user_cannot_create_article(): void
    {
        $response = $this->get('/wiki/articles/create');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test Kategorie-Anzeige
     */
    public function test_category_shows_articles(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'name' => 'Test Kategorie',
            'slug' => 'test-kategorie',
        ]);
        
        Article::factory()->create([
            'title' => 'Artikel in Kategorie',
            'status' => 'published',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        
        $response = $this->get('/wiki/category/' . $category->slug);
        
        $response->assertStatus(200);
        $response->assertSee('Test Kategorie');
        $response->assertSee('Artikel in Kategorie');
    }

    /**
     * Test Tag-System
     */
    public function test_tag_system_works(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $tag = Tag::factory()->create([
            'name' => 'PHP',
            'slug' => 'php',
        ]);
        
        $article = Article::factory()->create([
            'title' => 'PHP Tutorial',
            'status' => 'published',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        
        $article->tags()->attach($tag->id);
        
        $response = $this->get('/wiki/tag/' . $tag->slug);
        
        $response->assertStatus(200);
        $response->assertSee('PHP');
        $response->assertSee('PHP Tutorial');
    }

    /**
     * Test Middleware-Schutz
     */
    public function test_wiki_security_middleware_active(): void
    {
        $response = $this->get('/wiki');
        
        // Prüfen ob Sicherheits-Headers gesetzt sind
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    /**
     * Test Spam-Schutz
     */
    public function test_honeypot_middleware_blocks_spam(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Simuliere Spam-Bot mit Honeypot-Feld
        $spamData = [
            'title' => 'Spam Artikel',
            'content' => 'Dies ist Spam',
            'email_address' => 'spam@example.com', // Honeypot-Feld
        ];
        
        $response = $this->post('/wiki/articles', $spamData);
        
        // Sollte blockiert werden
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        // Artikel sollte nicht erstellt werden
        $this->assertDatabaseMissing('articles', [
            'title' => 'Spam Artikel',
        ]);
    }

    /**
     * Test Rate-Limiting
     */
    public function test_rate_limiting_works(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        
        // Mehrere Requests schnell hintereinander
        for ($i = 0; $i < 5; $i++) {
            $response = $this->get('/wiki/search?q=test');
            $response->assertStatus(200);
        }
        
        // Sollte immer noch funktionieren (Rate-Limit nicht überschritten)
        $response = $this->get('/wiki/search?q=test');
        $response->assertStatus(200);
    }
}
