<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Homestay;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index()
    {
        $static = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => url('village'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => url('tour-packages'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => url('homestay'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => url('events'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => url('news'), 'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => url('company-profile'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => url('services'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => url('our-team'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => url('v-founding'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => url('v-board'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => url('v-portofolio'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => url('our-partner'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => url('faq'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => url('contact'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => url('term'), 'priority' => '0.2', 'changefreq' => 'yearly'],
        ];

        $villages = User::join('village_details', 'village_details.user_id', 'users.id')
            ->where('users.role_id', '2')->where('users.is_active', '1')
            ->whereNotNull('village_details.slug')
            ->get(['village_details.slug'])
            ->map(fn ($v) => ['loc' => url('village/' . $v->slug), 'priority' => '0.8', 'changefreq' => 'weekly'])
            ->all();

        $packages = Package::where('is_active', '1')->whereNotNull('slug')
            ->get(['slug', 'updated_at'])
            ->map(fn ($p) => ['loc' => url('tour-packages/' . $p->slug), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => optional($p->updated_at)->toDateString()])
            ->all();

        $events = Event::where('is_active', '1')->whereNotNull('slug')
            ->get(['slug', 'updated_at'])
            ->map(fn ($e) => ['loc' => url('events/' . $e->slug), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => optional($e->updated_at)->toDateString()])
            ->all();

        $homestays = Homestay::where('is_active', '1')->whereNotNull('slug')
            ->get(['id', 'slug', 'updated_at'])
            ->map(fn ($h) => ['loc' => url('homestay/' . ($h->slug ?: $h->id)), 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => optional($h->updated_at)->toDateString()])
            ->all();

        $blogs = Blog::where('isPublished', '1')->whereNotNull('slug')
            ->get(['slug', 'updated_at'])
            ->map(fn ($b) => ['loc' => url('news/' . $b->slug), 'priority' => '0.7', 'changefreq' => 'weekly', 'lastmod' => optional($b->updated_at)->toDateString()])
            ->all();

        $urls = array_merge($static, $villages, $packages, $events, $homestays, $blogs);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . '</loc>' . "\n";
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
}