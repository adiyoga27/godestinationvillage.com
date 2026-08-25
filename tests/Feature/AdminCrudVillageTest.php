<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminCrudVillageTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('role_id', 1)->firstOrFail();
    }

    public function test_user_village_crud(): void
    {
        $email = 'smoke-village-' . time() . '@godevi.com';
        $villageName = 'Desa Smoke ' . time();
        try {
            $this->actingAs($this->admin)
                ->post('/administrator/user-village', [
                    'name' => 'Smoke Village Owner', 'email' => $email, 'password' => 'password123',
                    'password_confirmation' => 'password123', 'phone' => '0811111', 'role_id' => 2, 'is_active' => 1,
                    'village_name' => $villageName, 'village_address' => 'Bali', 'lat' => -8.5, 'lng' => 115.2,
                    'contact_person' => '0811111', 'desc' => '<p>desa wisata</p>',
                    'bank_name' => 'BCA', 'bank_acc_name' => 'Smoke', 'bank_acc_no' => '12345',
                ])->assertSessionHasNoErrors();

            $user = DB::table('users')->where('email', $email)->first();
            $this->assertNotNull($user, 'user-village tidak tersimpan');
            $vd = DB::table('village_details')->where('user_id', $user->id)->first();
            $this->assertNotNull($vd, 'village_details tidak tersimpan');

            $this->actingAs($this->admin)
                ->put('/administrator/user-village/' . $user->id, [
                    'name' => 'Smoke Village Upd', 'email' => $email, 'phone' => '0822222', 'role_id' => 2, 'is_active' => 1,
                    'village_name' => $villageName . ' Upd', 'village_address' => 'Bali', 'lat' => -8.5, 'lng' => 115.2,
                    'contact_person' => '0822222', 'desc' => '<p>update</p>',
                    'bank_name' => 'BNI', 'bank_acc_name' => 'Smoke', 'bank_acc_no' => '54321',
                ])->assertSessionHasNoErrors();
            $this->assertSame($villageName . ' Upd', DB::table('village_details')->where('user_id', $user->id)->value('village_name'));

            $this->actingAs($this->admin)->delete('/administrator/user-village/' . $user->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('users')->where('id', $user->id)->whereNull('deleted_at')->first());
        } finally {
            DB::table('users')->where('email', 'like', 'smoke-village-%@godevi.com')->delete();
            DB::table('village_details')->where('village_name', 'like', 'Desa Smoke%')->delete();
        }
    }

    public function test_events_store_smoke(): void
    {
        try {
            $img = UploadedFile::fake()->image('event.png', 100, 60);
            $resp = $this->actingAs($this->admin)
                ->post('/administrator/events', [
                    'name' => 'Event Smoke ' . time(), 'description' => '<p>x</p>',
                    'category_id' => DB::table('category_events')->value('id'),
                    'date_event' => '2026-12-01 10:00', 'price' => 100000, 'default_img' => $img,
                    'is_active' => 1,
                ]);
            $this->assertNotSame(500, $resp->status(), 'events store mengembalikan 500');
            $this->assertNotSame(404, $resp->status());
        } finally {
            DB::table('events')->where('name', 'like', 'Event Smoke%')->delete();
        }
    }

    public function test_certification_store_smoke(): void
    {
        try {
            $file = UploadedFile::fake()->create('cert.pdf', 100, 'application/pdf');
            $resp = $this->actingAs($this->admin)
                ->post('/administrator/surat', [
                    'category' => 'Test', 'reference_number' => 'REF-' . time(), 'date_at' => '2026-08-25',
                    'regarding' => 'Perihal', 'signer' => 'Smoke', 'departemen' => 'Umum',
                    'file' => $file, 'slug' => 'smoke-' . time(),
                ]);
            $this->assertNotSame(500, $resp->status(), 'surat store mengembalikan 500');
            $this->assertNotSame(404, $resp->status());
        } finally {
            DB::table('certification')->where('reference_number', 'like', 'REF-%')->delete();
        }
    }
}