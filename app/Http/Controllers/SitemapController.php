<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemapXml(): Response
    {
        $baseUrl = config('app.url', 'https://eloriawedding.test');

        $staticUrls = [
            [
                'loc' => $baseUrl,
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => $baseUrl . '/portfolio',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'loc' => $baseUrl . '/blog',
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
        ];

        $posts = Post::where('status', PostStatus::Published)
            ->latest('published_at')
            ->get(['slug', 'updated_at', 'published_at']);

        $blogUrls = $posts->map(function (Post $post) use ($baseUrl) {
            return [
                'loc' => $baseUrl . '/blog/' . $post->slug,
                'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        })->toArray();

        $urls = array_merge($staticUrls, $blogUrls);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($url['loc']) . '</loc>';
            $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
            $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $url['priority'] . '</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function robotsTxt(): Response
    {
        $baseUrl = config('app.url', 'https://eloriawedding.test');

        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Allow: /portfolio\n";
        $content .= "Allow: /blog\n";
        $content .= "Allow: /blog/*\n";
        $content .= "Disallow: /wedding/\n";
        $content .= "Disallow: /admin/\n\n";
        $content .= "Sitemap: " . $baseUrl . "/sitemap.xml\n";

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
