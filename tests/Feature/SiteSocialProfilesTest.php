<?php

namespace Tests\Feature;

use App\Helpers\Site;
use App\Models\SiteSetting;
use App\Models\User;
use Tests\TestCase;

/** LinkedIn & tourismtrends.org dari Pengaturan Website → footer & JSON-LD sameAs. */
class SiteSocialProfilesTest extends TestCase
{
    public function test_linkedin_from_admin_appears_in_footer_and_same_as(): void
    {
        $settings = SiteSetting::pluck('value', 'key')->all();
        $admin = User::where('role_id', 1)->firstOrFail();

        try {
            $this->actingAs($admin)->get(route('site-settings.index'))->assertOk()->assertSee('URL LinkedIn');

            $this->actingAs($admin)->put(route('site-settings.update'), ['settings' => ['linkedin' => 'bukan-url']])
                ->assertSessionHasErrors(['settings.linkedin']);

            $this->actingAs($admin)->put(route('site-settings.update'), ['settings' => array_merge($settings, [
                'linkedin' => 'https://www.linkedin.com/company/godevi-test/',
            ])])->assertSessionHasNoErrors();

            $this->get('http://localhost/id')
                ->assertSee('aria-label="LinkedIn"', false)
                ->assertSee('"sameAs":["', false)
                ->assertSee('"https://www.linkedin.com/company/godevi-test/"', false)
                ->assertSee('"https://tourismtrends.org/"', false);
        } finally {
            foreach ($settings as $key => $value) {
                SiteSetting::where('key', $key)->update(['value' => $value]);
            }
            Site::flush();
        }
    }

    public function test_linkedin_icon_hidden_when_empty(): void
    {
        $original = Site::get('linkedin');
        SiteSetting::where('key', 'linkedin')->update(['value' => null]);
        Site::flush();

        try {
            $this->get('http://localhost/id')->assertOk()->assertDontSee('aria-label="LinkedIn"', false);
        } finally {
            SiteSetting::where('key', 'linkedin')->update(['value' => $original]);
            Site::flush();
        }
    }
}
