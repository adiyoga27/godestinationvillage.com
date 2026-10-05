<?php

namespace Tests\Feature;

use App\Support\Locales;
use Tests\TestCase;

/** Struktur bahasa /id/ & /en/ (default /id) dengan hreflang, canonical & meta per bahasa. */
class LocalizedUrlsTest extends TestCase
{
    // URL absolut agar tidak ikut dilokalkan oleh url() milik test client.
    private function abs(string $path): string
    {
        return 'http://localhost'.$path;
    }

    public function test_old_urls_redirect_permanently_to_id(): void
    {
        $this->get($this->abs('/'))->assertStatus(301)->assertRedirect('http://localhost/id');
        $this->get($this->abs('/home'))->assertStatus(301);
        $this->get($this->abs('/faq?x=1'))->assertStatus(301)->assertRedirect('http://localhost/id/faq?x=1');
        $this->get($this->abs('/village/some-village'))->assertStatus(301)->assertRedirect('http://localhost/id/village/some-village');
        $this->get($this->abs('/asesmen/pariwisata'))->assertStatus(301)->assertRedirect('http://localhost/id/asesmen/pariwisata');
        // Form lama yang masih terbuka: method & isi ikut (308).
        $this->post($this->abs('/contact/send'))->assertStatus(308)->assertRedirect('http://localhost/id/contact/send');
    }

    public function test_assessment_is_indonesian_only(): void
    {
        $this->get($this->abs('/en/asesmen'))->assertStatus(301)->assertRedirect('http://localhost/id/asesmen');
        $this->get($this->abs('/id/asesmen'))->assertOk()->assertDontSee('<link rel="alternate" hreflang="en"', false);
    }

    public function test_transactional_and_admin_urls_keep_no_prefix(): void
    {
        $this->get($this->abs('/administrator/login'))->assertOk();
        $this->get($this->abs('/xx/faq'))->assertNotFound();
        $this->assertSame('/booking/1', Locales::localizePath('/booking/1'));
        $this->assertSame('/storage/a.png', Locales::localizePath('storage/a.png'));
    }

    public function test_each_language_has_own_canonical_hreflang_and_meta(): void
    {
        $en = $this->get($this->abs('/en/faq'))->assertOk();
        $en->assertSee('<html lang="en"', false)
            ->assertSee('<link rel="canonical" href="http://localhost/en/faq">', false)
            ->assertSee('<link rel="alternate" hreflang="id" href="http://localhost/id/faq">', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="http://localhost/en/faq">', false)
            ->assertSee('<link rel="alternate" hreflang="x-default" href="http://localhost/id/faq">', false)
            ->assertSee('<title>Frequently Asked Questions | GODEVI</title>', false)
            ->assertSee('href="http://localhost/en/village"', false);

        $this->get($this->abs('/id/faq'))->assertOk()
            ->assertSee('<html lang="id"', false)
            ->assertSee('<link rel="canonical" href="http://localhost/id/faq">', false)
            ->assertSee('<title>Pertanyaan yang Sering Diajukan | GODEVI</title>', false)
            ->assertSee('href="http://localhost/id/village"', false);
    }

    public function test_language_switcher_points_to_same_page_in_other_language(): void
    {
        $this->get($this->abs('/id/faq?x=1'))->assertSee('href="http://localhost/en/faq?x=1" hreflang="en"', false);

        // Rute lama /locale/en dari halaman berbahasa → halaman yang sama versi EN.
        $this->withHeader('referer', 'http://localhost/id/news?page=2')
            ->get($this->abs('/locale/en'))->assertRedirect('http://localhost/en/news?page=2');
    }

    public function test_sitemap_lists_both_languages_with_alternates(): void
    {
        $xml = $this->get($this->abs('/sitemap.xml'))->assertOk()->getContent();

        $this->assertNotFalse(simplexml_load_string($xml));
        $this->assertStringContainsString('<loc>http://localhost/id/faq</loc>', $xml);
        $this->assertStringContainsString('<loc>http://localhost/en/faq</loc>', $xml);
        $this->assertStringContainsString('hreflang="x-default" href="http://localhost/id/faq"', $xml);
        $this->assertStringContainsString('<loc>http://localhost/id/asesmen</loc>', $xml);
        $this->assertStringNotContainsString('/en/asesmen', $xml);
    }
}
