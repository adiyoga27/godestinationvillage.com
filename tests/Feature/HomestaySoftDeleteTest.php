<?php

namespace Tests\Feature;

use App\Models\Homestay;
use App\Models\OrderHomestay;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

/** Hapus homestay dari admin = soft delete; homestay yang punya order tetap bisa dihapus. */
class HomestaySoftDeleteTest extends TestCase
{
    public function test_homestay_with_orders_is_soft_deleted(): void
    {
        $admin = User::where('role_id', 1)->firstOrFail();
        $name = 'Hapus Uji '.uniqid();
        $homestay = Homestay::create([
            'name' => $name, 'description' => 'x', 'location' => 'x', 'price' => 300000, 'disc' => 0,
            'owner_name' => 'x', 'check_in_time' => '14.00', 'check_out_time' => '12.00', 'is_active' => 1,
            'category_id' => 1, 'slug' => Str::slug($name),
        ]);
        $order = OrderHomestay::create([
            'homestay_id' => $homestay->id, 'code' => 'UJI'.uniqid(), 'uuid' => (string) Str::uuid(),
            'customer_name' => 'Uji', 'customer_email' => 'uji@example.com', 'customer_phone' => '0812',
            'customer_address' => 'x', 'homestay_name' => $name, 'homestay_price' => 300000, 'homestay_discount' => 0,
            'total_payment' => 300000, 'payment_status' => 'pending', 'pax' => 1,
        ]);

        try {
            $this->actingAs($admin)->delete(route('homestay.destroy', $homestay->id))
                ->assertRedirect(route('homestay.index'))
                ->assertSessionHas('status', 'Homestay berhasil dihapus');

            // Baris tetap ada (deleted_at terisi), tapi hilang dari query biasa & halaman publik.
            $this->assertNull(Homestay::find($homestay->id));
            $this->assertNotNull(Homestay::withTrashed()->find($homestay->id)?->deleted_at);
            $this->assertSame(0, $this->get('http://localhost/en/homestay?q='.urlencode($name))->viewData('packages')->total());
            $this->get('http://localhost/en/homestay/'.$homestay->id)->assertNotFound();

            // Riwayat order tetap menampilkan homestay-nya.
            $this->assertSame($name, $order->fresh()->package?->name);

            // Hapus ulang → pesan jelas, bukan error 500.
            $this->actingAs($admin)->delete(route('homestay.destroy', $homestay->id))
                ->assertRedirect(route('homestay.index'))
                ->assertSessionHas('error');
        } finally {
            $order->forceDelete();
            Homestay::withTrashed()->where('id', $homestay->id)->forceDelete();
        }
    }
}
