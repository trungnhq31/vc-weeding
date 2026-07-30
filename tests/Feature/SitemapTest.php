<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_returns_valid_xml_response(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<urlset', false);
        $response->assertSee('<loc>', false);
        $response->assertSee('/portfolio', false);
        $response->assertSee('/blog', false);
    }

    public function test_robots_txt_returns_plain_text_with_sitemap_and_disallow_rules(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('User-agent: *');
        $response->assertSee('Disallow: /wedding/');
        $response->assertSee('Disallow: /admin/');
        $response->assertSee('Sitemap:');
    }
}
