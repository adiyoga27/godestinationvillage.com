<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Homestay;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\OurTeam;
use App\Models\Package;
use App\Models\User;
use App\Models\VillageSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class GuestBookingFlowsTest extends TestCase
{
    public function test_village_submission_guest_flow(): void
    {
        $this->get('/daftar-desa')->assertStatus(200);

        $unique = 'Desa Test '.time();
        $email = 'desa'.time().'@example.com';

        $store = $this->post(route('village-submission.store'), [
            'village_name' => $unique,
            'contact_name' => 'Made Test',
            'email' => $email,
            'phone' => '08123456789',
            'address' => 'Jl. Test No. 1',
            'regency' => 'Gianyar',
            'description' => 'Desa test',
            'tourism_potential' => 'Air terjun',
        ]);
        $store->assertRedirect();

        try {
            $submission = VillageSubmission::where('email', $email)->first();
            $this->assertNotNull($submission, 'village submission tidak tersimpan');
            $this->assertSame('pending', $submission->status);
            $this->get(route('village-submission.success', $submission->uuid))->assertStatus(200);
        } finally {
            VillageSubmission::where('email', $email)->delete();
        }
    }

    public function test_guest_package_booking_creates_order_without_login(): void
    {
        Mail::fake();

        $package = Package::first();
        if (! $package) {
            $this->markTestSkipped('Tidak ada data package untuk diuji.');
        }

        $this->get(route('guest-booking.package.form', $package->slug))->assertStatus(200);

        $email = 'tamu'.time().'@example.com';
        $response = $this->post(route('guest-booking.package.store'), [
            'idtour' => $package->id,
            'customername' => 'Tamu Guest',
            'email' => $email,
            'address' => 'Jl. Tamu',
            'phone' => '0811111111',
            'pax' => 2,
        ]);

        try {
            $order = Order::where('customer_email', $email)->latest('id')->first();
            $this->assertNotNull($order, 'order paket guest tidak tersimpan');
            $response->assertRedirect('payment/package/'.$order->uuid);
        } finally {
            Order::where('customer_email', $email)->delete();
        }
    }

    public function test_guest_event_free_booking_skips_midtrans(): void
    {
        Mail::fake();

        $event = Event::where('is_free', 1)->first();
        if (! $event) {
            $this->markTestSkipped('Tidak ada event gratis untuk diuji.');
        }

        $email = 'gratis'.time().'@example.com';
        $response = $this->post(route('guest-booking.event.store'), [
            'idevent' => $event->id,
            'customername' => 'Peserta Gratis',
            'email' => $email,
            'address' => 'Jl. Gratis',
            'phone' => '0822222222',
            'pax' => 1,
        ]);

        try {
            $order = OrderEvent::where('customer_email', $email)->latest('id')->first();
            $this->assertNotNull($order, 'order event gratis tidak tersimpan');
            $this->assertSame('success', $order->payment_status);
            $response->assertRedirect('reservation-events/paid/'.$email);
        } finally {
            OrderEvent::where('customer_email', $email)->delete();
        }
    }

    public function test_guest_homestay_and_event_forms_load(): void
    {
        $event = Event::first();
        if ($event) {
            $this->get(route('guest-booking.event.form', $event->slug))->assertStatus(200);
        }

        $homestay = Homestay::first();
        if ($homestay) {
            $this->get(route('guest-booking.homestay.form', $homestay->id))->assertStatus(200);
        }

        if (! $event && ! $homestay) {
            $this->markTestSkipped('Tidak ada data event/homestay untuk diuji.');
        }
    }

    public function test_team_dashboard_requires_auth(): void
    {
        $this->get(route('team-dashboard.index'))->assertRedirect('/login');
        $this->get(route('village-submissions.index'))->assertRedirect('/login');
    }

    public function test_admin_can_verify_village_submission(): void
    {
        $admin = User::where('role_id', 1)->first();
        if (! $admin) {
            $this->markTestSkipped('Tidak ada admin untuk diuji.');
        }

        $team = OurTeam::first();
        if (! $team) {
            $this->markTestSkipped('Tidak ada data OurTeam untuk diuji.');
        }
        $email = 'verif'.time().'@example.com';
        $submission = VillageSubmission::create([
            'uuid' => (string) Str::uuid(),
            'village_name' => 'Desa Verifikasi',
            'contact_name' => 'Kontak',
            'email' => $email,
            'phone' => '0833333333',
            'address' => 'Alamat',
            'status' => 'pending',
        ]);

        try {
            $this->actingAs($admin)->get(route('team-dashboard.index'))->assertStatus(200);
            $this->actingAs($admin)->get(route('village-submissions.index'))->assertStatus(200);
            $this->actingAs($admin)->get(route('village-submissions.show', $submission->id))->assertStatus(200);

            $this->actingAs($admin)->put(route('village-submissions.update', $submission->id), [
                'status' => 'verified',
                'pic_team_id' => $team->id,
                'internal_note' => 'Sudah survei awal.',
            ])->assertRedirect();

            $this->assertDatabaseHas('village_submissions', [
                'id' => $submission->id,
                'status' => 'verified',
                'pic_team_id' => $team->id,
            ]);

            $this->actingAs($admin)->post(route('team-dashboard.assign'), [
                'type' => 'village_submission',
                'id' => $submission->id,
                'pic_team_id' => $team->id,
                'internal_note' => 'Assign via dashboard',
                'status' => 'verified',
            ])->assertSessionHasNoErrors();
        } finally {
            VillageSubmission::where('id', $submission->id)->delete();
            DB::table('activity_log')->where('log_name', 'default')->delete();
        }
    }
}
