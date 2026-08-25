<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudSmokeTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('role_id', 1)->firstOrFail();
    }

    public function test_admin_pages_load(): void
    {
        $pages = [
            '/administrator/dashboard',
            '/administrator/bank-account',
            '/administrator/news',
            '/administrator/surat',
            '/administrator/instagram',
            '/administrator/slider',
            '/administrator/booklet',
            '/administrator/review',
            '/administrator/category',
            '/administrator/category-event',
            '/administrator/discount-member',
            '/administrator/orders',
            '/administrator/order-event',
            '/administrator/order-homestay',
            '/administrator/category-events',
            '/administrator/events',
            '/administrator/founding',
            '/administrator/ourteam',
            '/administrator/boardexpert',
            '/administrator/portofolio',
            '/administrator/homestay',
            '/administrator/category-homestay',
            '/administrator/package',
            '/administrator/user-admin',
            '/administrator/user-member',
            '/administrator/user-village',
            '/administrator/profile',
            '/administrator/report/villages',
            '/administrator/report/events',
            '/administrator/news/create',
            '/administrator/category/create',
            '/administrator/slider/create',
            '/administrator/package/create',
            '/administrator/homestay/create',
            '/administrator/events/create',
        ];

        $failures = [];
        foreach ($pages as $page) {
            $response = $this->actingAs($this->admin)->get($page);
            if ($response->status() !== 200) {
                $failures[] = "{$page} => {$response->status()}";
            }
        }

        $this->assertEmpty($failures, "Halaman gagal dimuat:\n" . implode("\n", $failures));
    }

    public function test_admin_datatables_ajax(): void
    {
        $endpoints = [
            '/administrator/bank-account',
            '/administrator/news',
            '/administrator/surat',
            '/administrator/instagram',
            '/administrator/slider',
            '/administrator/review',
            '/administrator/category',
            '/administrator/category-event',
            '/administrator/discount-member',
            '/administrator/orders',
            '/administrator/order-event',
            '/administrator/order-homestay',
            '/administrator/category-events',
            '/administrator/events',
            '/administrator/founding',
            '/administrator/ourteam',
            '/administrator/boardexpert',
            '/administrator/portofolio',
            '/administrator/homestay',
            '/administrator/category-homestay',
            '/administrator/package',
            '/administrator/user-admin',
            '/administrator/user-member',
            '/administrator/user-village',
        ];

        $failures = [];
        foreach ($endpoints as $endpoint) {
            $response = $this->actingAs($this->admin)->get($endpoint, ['X-Requested-With' => 'XMLHttpRequest']);
            if ($response->status() !== 200) {
                $failures[] = "{$endpoint} (ajax) => {$response->status()}";
            } else {
                $json = json_decode($response->getContent(), true);
                if (is_array($json) && !empty($json['error'])) {
                    $failures[] = "{$endpoint} (ajax) => error: " . substr($json['error'], 0, 120);
                }
            }
        }

        $this->assertEmpty($failures, "Datatable gagal:\n" . implode("\n", $failures));
    }
}