<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageRoutesTest extends TestCase
{
    /**
     * Test homepage loads successfully.
     */
    public function test_homepage_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('pages.home');
        $response->assertSee('KI-Coding');
        $response->assertSee('Programmieren mit');
    }

    /**
     * Test services page loads successfully.
     */
    public function test_services_page_loads(): void
    {
        $response = $this->get('/services');
        $response->assertStatus(200);
        $response->assertViewIs('pages.services');
        $response->assertSee('Services');
    }

    /**
     * Test about page loads successfully.
     */
    public function test_about_page_loads(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertViewIs('pages.about');
        $response->assertSee('Über uns');
    }

    /**
     * Test contact page loads successfully.
     */
    public function test_contact_page_loads(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertViewIs('pages.contact');
        $response->assertSee('Kontakt');
    }

    /**
     * Test privacy page loads successfully.
     */
    public function test_privacy_page_loads(): void
    {
        $response = $this->get('/privacy');
        $response->assertStatus(200);
        $response->assertViewIs('pages.privacy');
        $response->assertSee('Datenschutz');
    }

    /**
     * Test imprint page loads successfully.
     */
    public function test_imprint_page_loads(): void
    {
        $response = $this->get('/imprint');
        $response->assertStatus(200);
        $response->assertViewIs('pages.imprint');
        $response->assertSee('Impressum');
    }

    /**
     * Test SEO meta tags are present.
     */
    public function test_seo_meta_tags(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<meta property="og:title"', false);
        $response->assertSee('<meta property="og:description"', false);
    }

    /**
     * Test robots.txt file exists.
     */
    public function test_robots_txt_exists(): void
    {
        $robotsPath = public_path('robots.txt');
        $this->assertFileExists($robotsPath);
        $this->assertStringContainsString('User-agent', file_get_contents($robotsPath));
    }

    /**
     * Test sitemap.xml file exists.
     */
    public function test_sitemap_xml_exists(): void
    {
        $sitemapPath = public_path('sitemap.xml');
        $this->assertFileExists($sitemapPath);
        $this->assertStringContainsString('<?xml version="1.0"', file_get_contents($sitemapPath));
        $this->assertStringContainsString('<urlset', file_get_contents($sitemapPath));
    }
}
