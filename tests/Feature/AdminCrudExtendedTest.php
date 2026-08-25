<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminCrudExtendedTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('role_id', 1)->firstOrFail();
    }

    public function test_news_crud(): void
    {
        $title = 'News Smoke ' . Str::random(6);
        try {
            $this->actingAs($this->admin)
                ->post('/administrator/news', ['post_title' => $title, 'post_content' => '<p>isi</p>', 'isPublished' => 1])
                ->assertSessionHasNoErrors();
            $row = DB::table('post')->where('post_title', $title)->first();
            $this->assertNotNull($row, 'news tidak tersimpan');

            $this->actingAs($this->admin)
                ->put('/administrator/news/' . $row->id, ['post_title' => $title . ' Upd', 'post_content' => '<p>isi2</p>', 'isPublished' => 1])
                ->assertSessionHasNoErrors();
            $this->assertSame($title . ' Upd', DB::table('post')->where('id', $row->id)->value('post_title'));

            $this->actingAs($this->admin)->delete('/administrator/news/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('post')->where('id', $row->id)->first());
        } finally {
            DB::table('post')->where('post_title', 'like', 'News Smoke%')->delete();
        }
    }

    public function test_user_admin_crud(): void
    {
        $email = 'smoke-admin-' . time() . '@godevi.com';
        try {
            $this->actingAs($this->admin)
                ->post('/administrator/user-admin', [
                    'name' => 'Smoke Admin', 'email' => $email, 'password' => 'password123',
                    'password_confirmation' => 'password123', 'phone' => '0811111', 'role_id' => 1, 'is_active' => 1,
                ])->assertSessionHasNoErrors();
            $row = DB::table('users')->where('email', $email)->first();
            $this->assertNotNull($row, 'user-admin tidak tersimpan');

            $this->actingAs($this->admin)
                ->put('/administrator/user-admin/' . $row->id, [
                    'name' => 'Smoke Admin Upd', 'email' => $email, 'phone' => '0822222', 'role_id' => 1, 'is_active' => 1,
                ])->assertSessionHasNoErrors();
            $this->assertSame('Smoke Admin Upd', DB::table('users')->where('id', $row->id)->value('name'));

            $this->actingAs($this->admin)->delete('/administrator/user-admin/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('users')->where('id', $row->id)->whereNull('deleted_at')->first());
        } finally {
            DB::table('users')->where('email', 'like', 'smoke-admin-%@godevi.com')->delete();
        }
    }

    public function test_user_member_crud(): void
    {
        $email = 'smoke-member-' . time() . '@godevi.com';
        try {
            $this->actingAs($this->admin)
                ->post('/administrator/user-member', [
                    'name' => 'Smoke Member', 'email' => $email, 'password' => 'password123',
                    'password_confirmation' => 'password123', 'phone' => '0811111', 'role_id' => 3, 'is_active' => 1,
                ])->assertSessionHasNoErrors();
            $row = DB::table('users')->where('email', $email)->first();
            $this->assertNotNull($row, 'user-member tidak tersimpan');

            $this->actingAs($this->admin)->delete('/administrator/user-member/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('users')->where('id', $row->id)->whereNull('deleted_at')->first());
        } finally {
            DB::table('users')->where('email', 'like', 'smoke-member-%@godevi.com')->delete();
        }
    }

    public function test_booklet_store(): void
    {
        try {
            $pdf = \Illuminate\Http\UploadedFile::fake()->create('booklet.pdf', 100, 'application/pdf');
            $this->actingAs($this->admin)
                ->post('/administrator/booklet', ['pdf' => $pdf])
                ->assertSessionHasNoErrors();
        } catch (\Throwable $e) {
            $this->fail('booklet store gagal: ' . $e->getMessage());
        }
    }
}