<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Homestay;
use App\Models\Package;
use App\Models\User;
use App\Support\Locales;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index()
    {
        $static = [
            ['path' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['path' => 'village', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => 'tour-packages', 'priority' => '0.9', 'changefreq' => 'daily'],
            ['path' => 'homestay', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => 'events', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => 'news', 'priority' => '0.8', 'changefreq' => 'daily'],
            ['path' => 'company-profile', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['path' => 'services', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['path' => 'our-team', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => 'v-founding', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['path' => 'v-board', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => 'v-portofolio', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['path' => 'our-partner', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => 'faq', 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['path' => 'contact', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['path' => 'asesmen', 'priority' => '0.8', 'changefreq' => 'monthly'],
        ];

        $villages = User::join('village_details', 'village_details.user_id', 'users.id')
            ->where('users.role_id', '2')->where('users.is_active', '1')
            ->whereNotNull('village_details.slug')
            ->get(['village_details.slug'])
            ->map(fn ($v) => ['path' => 'village/' . $v->slug, 'priority' => '0.8', 'changefreq' => 'weekly'])
            ->all();

        $packages = Package::where('is_active', '1')->whereNotNull('slug')
            ->get(['slug', 'updated_at'])
            ->map(fn ($p) => ['path' => 'tour-packages/' . $p->slug, 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => optional($p->updated_at)->toDateString()])
            ->all();

        $events = Event::where('is_active', '1')->whereNotNull('slug')
            ->get(['slug', 'updated_at'])
            ->map(fn ($e) => ['path' => 'events/' . $e->slug, 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => optional($e->updated_at)->toDateString()])
            ->all();

        $homestays = Homestay::where('is_active', '1')->whereNotNull('slug')
            ->get(['id', 'slug', 'updated_at'])
            ->map(fn ($h) => ['path' => 'homestay/' . ($h->slug ?: $h->id), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => optional($h->updated_at)->toDateString()])
            ->all();

        $blogs = Blog::where('isPublished', '1')->whereNotNull('slug')
            ->get(['slug', 'updated_at'])
            ->map(fn ($b) => ['path' => 'news/' . $b->slug, 'priority' => '0.7', 'changefreq' => 'weekly', 'lastmod' => optional($b->updated_at)->toDateString()])
            ->all();

        $urls = array_merge($static, $villages, $packages, $events, $homestays, $blogs);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
        foreach ($this->perLocale($urls) as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . '</loc>' . "\n";
            foreach ($u['alternates'] as $hreflang => $href) {
                $xml .= '    <xhtml:link rel="alternate" hreflang="' . $hreflang . '" href="' . htmlspecialchars($href, ENT_XML1) . '"/>' . "\n";
            }
            if (!empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . "\n";
            }
            $xml .= '    <changefreq>' . ($u['changefreq'] ?? 'weekly') . '</changefreq>' . "\n";
            $xml .= '    <priority>' . ($u['priority'] ?? '0.5') . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Satu entri per bahasa (/id/... & /en/...) lengkap dengan hreflang alternates.
     * Halaman khusus Bahasa Indonesia (asesmen) hanya /id/... tanpa alternates.
     */
    private function perLocale(array $urls): array
    {
        $out = [];
        foreach ($urls as $u) {
            $idOnly = in_array(explode('/', trim($u['path'], '/'))[0], Locales::ID_ONLY_SEGMENTS, true);
            $locales = $idOnly ? ['id'] : Locales::SUPPORTED;

            $alternates = [];
            foreach ($locales as $locale) {
                $alternates[$locale] = url(Locales::localizePath($u['path'], $locale));
            }
            if (! $idOnly) {
                $alternates['x-default'] = $alternates[Locales::DEFAULT];
            }

            foreach ($locales as $locale) {
                $out[] = $u + ['loc' => $alternates[$locale], 'alternates' => $idOnly ? [] : $alternates];
            }
        }

        return $out;
    }
}