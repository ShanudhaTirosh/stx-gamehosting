<?php

namespace BrandedHosting\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use BrandedHosting\Platform\Models\HostingCategory;
use BrandedHosting\Platform\Models\HostingPlan;
use BrandedHosting\Platform\Models\HostingSeoSetting;
use BrandedHosting\Platform\Services\KnowledgeBaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Generate dynamic, search-engine compliant XML sitemap.
     */
    public function sitemap(Request $request): Response
    {
        $setting = HostingSeoSetting::current();
        $baseUrl = url('/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Core Static Pages
        $staticPages = [
            ['url' => $baseUrl . '/', 'priority' => '1.0', 'freq' => 'daily'],
            ['url' => $baseUrl . '/games', 'priority' => '0.9', 'freq' => 'daily'],
            ['url' => $baseUrl . '/status', 'priority' => '0.8', 'freq' => 'hourly'],
            ['url' => $baseUrl . '/help', 'priority' => '0.8', 'freq' => 'weekly'],
            ['url' => $baseUrl . '/about', 'priority' => '0.6', 'freq' => 'monthly'],
            ['url' => $baseUrl . '/contact', 'priority' => '0.7', 'freq' => 'monthly'],
            ['url' => $baseUrl . '/terms', 'priority' => '0.5', 'freq' => 'monthly'],
            ['url' => $baseUrl . '/privacy', 'priority' => '0.5', 'freq' => 'monthly'],
        ];

        foreach ($staticPages as $page) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($page['url']) . "</loc>\n";
            $xml .= "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
            $xml .= "    <changefreq>" . $page['freq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $page['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        // Categories
        $categories = HostingCategory::where('is_visible', true)->get();
        foreach ($categories as $cat) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/hosting/' . $cat->slug) . "</loc>\n";
            $xml .= "    <lastmod>" . $cat->updated_at->format('Y-m-d') . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.9</priority>\n";
            $xml .= "  </url>\n";
        }

        // Hosting Plans
        $plans = HostingPlan::where('is_available', true)->where('is_search_indexed', true)->get();
        foreach ($plans as $plan) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/checkout/' . $plan->slug) . "</loc>\n";
            $xml .= "    <lastmod>" . $plan->updated_at->format('Y-m-d') . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        // Knowledge Base Articles
        $kbService = new KnowledgeBaseService();
        foreach ($kbService->getCategories() as $catSlug => $cat) {
            foreach ($cat['articles'] ?? [] as $artSlug => $article) {
                $xml .= "  <url>\n";
                $xml .= "    <loc>" . htmlspecialchars($baseUrl . '/knowledgebase/article/' . $catSlug . '/' . $artSlug) . "</loc>\n";
                $xml .= "    <lastmod>" . date('Y-m-d') . "</lastmod>\n";
                $xml .= "    <changefreq>weekly</changefreq>\n";
                $xml .= "    <priority>0.7</priority>\n";
                $xml .= "  </url>\n";
            }
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Generate dynamic robots.txt honoring admin robots policy.
     */
    public function robots(Request $request): Response
    {
        $setting = HostingSeoSetting::current();
        $sitemapUrl = url('/sitemap.xml');

        if ($setting->robots_policy === 'noindex, nofollow') {
            $content = "User-agent: *\nDisallow: /\n";
        } else {
            $content = "User-agent: *\n";
            $content .= "Allow: /\n";
            $content .= "Disallow: /admin\n";
            $content .= "Disallow: /server/\n";
            $content .= "Disallow: /api/\n";
            $content .= "Disallow: /password-reset/\n";
            $content .= "\n";
            $content .= "Sitemap: {$sitemapUrl}\n";
        }

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
