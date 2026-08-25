<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminCrudOperationsTest extends TestCase
{
    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('role_id', 1)->firstOrFail();
    }

    private function postStore(string $url, array $payload)
    {
        return $this->actingAs($this->admin)->post($url, $payload);
    }

    private function putUpdate(string $url, array $payload)
    {
        return $this->actingAs($this->admin)->put($url, $payload);
    }

    private function deleteRow(string $url)
    {
        return $this->actingAs($this->admin)->delete($url);
    }

    public function test_category_crud(): void
    {
        $unique = 'Cat Smoke ' . time();
        try {
            $this->postStore('/administrator/category', ['name' => $unique])->assertSessionHasNoErrors();
            $row = DB::table('categories')->where('name', $unique)->first();
            $this->assertNotNull($row, 'category tidak tersimpan');
            $this->putUpdate('/administrator/category/' . $row->id, ['name' => $unique . ' Upd'])->assertSessionHasNoErrors();
            $this->assertSame($unique . ' Upd', DB::table('categories')->where('id', $row->id)->value('name'));
            $this->deleteRow('/administrator/category/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('categories')->where('id', $row->id)->whereNull('deleted_at')->first());
        } finally {
            DB::table('categories')->where('name', 'like', 'Cat Smoke%')->delete();
        }
    }

    public function test_category_event_crud(): void
    {
        $unique = 'CatEvent Smoke ' . time();
        try {
            $this->postStore('/administrator/category-event', ['name' => $unique, 'description' => 'desc'])->assertSessionHasNoErrors();
            $row = DB::table('category_events')->where('name', $unique)->first();
            $this->assertNotNull($row, 'category-event tidak tersimpan');
            $this->putUpdate('/administrator/category-event/' . $row->id, ['name' => $unique . ' Upd', 'description' => 'desc2'])->assertSessionHasNoErrors();
            $this->assertSame('desc2', DB::table('category_events')->where('id', $row->id)->value('description'));
            $this->deleteRow('/administrator/category-event/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('category_events')->where('id', $row->id)->first());
        } finally {
            DB::table('category_events')->where('name', 'like', 'CatEvent Smoke%')->delete();
        }
    }

    public function test_category_homestay_crud(): void
    {
        $unique = 'CatHome Smoke ' . time();
        try {
            $this->postStore('/administrator/category-homestay', ['name' => $unique, 'description' => 'desc'])->assertSessionHasNoErrors();
            $row = DB::table('category_homestay')->where('name', $unique)->first();
            $this->assertNotNull($row, 'category-homestay tidak tersimpan');
            $this->putUpdate('/administrator/category-homestay/' . $row->id, ['name' => $unique . ' Upd', 'description' => 'desc2'])->assertSessionHasNoErrors();
            $this->assertSame('desc2', DB::table('category_homestay')->where('id', $row->id)->value('description'));
            $this->deleteRow('/administrator/category-homestay/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('category_homestay')->where('id', $row->id)->first());
        } finally {
            DB::table('category_homestay')->where('name', 'like', 'CatHome Smoke%')->delete();
        }
    }

    public function test_slider_crud(): void
    {
        $unique = 'Slider Smoke ' . time();
        try {
            $img = UploadedFile::fake()->image('slider.png', 100, 60);
            $this->postStore('/administrator/slider', ['title' => $unique, 'desc' => 'desc', 'img' => $img])->assertSessionHasNoErrors();
            $row = DB::table('slider')->where('title', $unique)->first();
            $this->assertNotNull($row, 'slider tidak tersimpan');
            $this->putUpdate('/administrator/slider/' . $row->id, ['title' => $unique . ' Upd', 'desc' => 'desc2'])->assertSessionHasNoErrors();
            $this->assertSame($unique . ' Upd', DB::table('slider')->where('id', $row->id)->value('title'));
            $this->deleteRow('/administrator/slider/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('slider')->where('id', $row->id)->whereNull('deleted_at')->first());
        } finally {
            DB::table('slider')->where('title', 'like', 'Slider Smoke%')->delete();
        }
    }

    public function test_bank_account_crud(): void
    {
        $unique = 'Bank Smoke ' . time();
        try {
            $this->postStore('/administrator/bank-account', ['bank_name' => 'BCA', 'bank_acc_name' => $unique, 'bank_acc_no' => '1234567890'])->assertSessionHasNoErrors();
            $row = DB::table('bank_accounts')->where('bank_acc_name', $unique)->first();
            $this->assertNotNull($row, 'bank-account tidak tersimpan');
            $this->putUpdate('/administrator/bank-account/' . $row->id, ['bank_name' => 'BNI', 'bank_acc_name' => $unique . ' Upd', 'bank_acc_no' => '0987654321'])->assertSessionHasNoErrors();
            $this->assertSame('BNI', DB::table('bank_accounts')->where('id', $row->id)->value('bank_name'));
            $this->deleteRow('/administrator/bank-account/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('bank_accounts')->where('id', $row->id)->whereNull('deleted_at')->first());
        } finally {
            DB::table('bank_accounts')->where('bank_acc_name', 'like', 'Bank Smoke%')->delete();
        }
    }

    public function test_discount_member_crud(): void
    {
        $unique = 'Disc Smoke ' . time();
        try {
            $this->postStore('/administrator/discount-member', ['type' => 'fix', 'value' => 15, 'is_active' => 0])->assertSessionHasNoErrors();
            $row = DB::table('member_discounts')->where('value', 15)->orderByDesc('id')->first();
            $this->assertNotNull($row, 'discount-member tidak tersimpan');
            $this->putUpdate('/administrator/discount-member/' . $row->id, ['type' => 'percentage', 'value' => 20, 'is_active' => 0])->assertSessionHasNoErrors();
            $this->assertSame('percentage', DB::table('member_discounts')->where('id', $row->id)->value('type'));
            $this->deleteRow('/administrator/discount-member/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('member_discounts')->where('id', $row->id)->whereNull('deleted_at')->first());
        } finally {
            DB::table('member_discounts')->whereIn('value', [15, 20])->whereNull('deleted_at')->delete();
        }
    }

    public function test_instagram_crud(): void
    {
        $unique = 'Insta Smoke ' . time();
        try {
            $this->postStore('/administrator/instagram', ['name' => $unique, 'url' => 'https://instagram.com/test', 'is_active' => 1])->assertSessionHasNoErrors();
            $row = DB::table('instagram')->where('name', $unique)->first();
            $this->assertNotNull($row, 'instagram tidak tersimpan');
            $this->putUpdate('/administrator/instagram/' . $row->id, ['name' => $unique . ' Upd', 'url' => 'https://instagram.com/test2', 'is_active' => 1])->assertSessionHasNoErrors();
            $this->assertSame('https://instagram.com/test2', DB::table('instagram')->where('id', $row->id)->value('url'));
            $this->deleteRow('/administrator/instagram/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('instagram')->where('id', $row->id)->first());
        } finally {
            DB::table('instagram')->where('name', 'like', 'Insta Smoke%')->delete();
        }
    }

    public function test_review_crud(): void
    {
        $unique = 'Review Smoke ' . time();
        try {
            $img = UploadedFile::fake()->image('review.png', 100, 60);
            $this->postStore('/administrator/review', ['rating' => 5, 'name' => $unique, 'comment' => 'bagus sekali', 'avatar' => $img])->assertSessionHasNoErrors();
            $row = DB::table('reviews')->where('name', $unique)->first();
            $this->assertNotNull($row, 'review tidak tersimpan');
            $this->putUpdate('/administrator/review/' . $row->id, ['rating' => 4, 'name' => $unique . ' Upd', 'comment' => 'update'])->assertSessionHasNoErrors();
            $this->assertSame(4, (int) DB::table('reviews')->where('id', $row->id)->value('rating'));
            $this->deleteRow('/administrator/review/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('reviews')->where('id', $row->id)->first());
        } finally {
            DB::table('reviews')->where('name', 'like', 'Review Smoke%')->delete();
        }
    }

    public function test_founding_crud(): void
    {
        $unique = 'Founding Smoke ' . time();
        try {
            $img = UploadedFile::fake()->image('founding.png', 100, 60);
            $this->postStore('/administrator/founding', ['name' => $unique, 'title' => 'Title', 'description' => 'desc', 'avatar' => $img])->assertSessionHasNoErrors();
            $row = DB::table('foundings')->where('name', $unique)->first();
            $this->assertNotNull($row, 'founding tidak tersimpan');
            $this->putUpdate('/administrator/founding/' . $row->id, ['name' => $unique . ' Upd', 'title' => 'Title2', 'description' => 'desc2'])->assertSessionHasNoErrors();
            $this->assertSame('Title2', DB::table('foundings')->where('id', $row->id)->value('title'));
            $this->deleteRow('/administrator/founding/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('foundings')->where('id', $row->id)->first());
        } finally {
            DB::table('foundings')->where('name', 'like', 'Founding Smoke%')->delete();
        }
    }

    public function test_ourteam_crud(): void
    {
        $unique = 'OurTeam Smoke ' . time();
        try {
            $img = UploadedFile::fake()->image('team.png', 100, 60);
            $this->postStore('/administrator/ourteam', ['name' => $unique, 'title' => 'Title', 'description' => 'desc', 'avatar' => $img])->assertSessionHasNoErrors();
            $row = DB::table('our_teams')->where('name', $unique)->first();
            $this->assertNotNull($row, 'ourteam tidak tersimpan');
            $this->putUpdate('/administrator/ourteam/' . $row->id, ['name' => $unique . ' Upd', 'title' => 'Title2', 'description' => 'desc2'])->assertSessionHasNoErrors();
            $this->assertSame('Title2', DB::table('our_teams')->where('id', $row->id)->value('title'));
            $this->deleteRow('/administrator/ourteam/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('our_teams')->where('id', $row->id)->first());
        } finally {
            DB::table('our_teams')->where('name', 'like', 'OurTeam Smoke%')->delete();
        }
    }

    public function test_boardexpert_crud(): void
    {
        $unique = 'Board Smoke ' . time();
        try {
            $img = UploadedFile::fake()->image('board.png', 100, 60);
            $this->postStore('/administrator/boardexpert', ['name' => $unique, 'title' => 'Title', 'description' => 'desc', 'avatar' => $img])->assertSessionHasNoErrors();
            $row = DB::table('board_experts')->where('name', $unique)->first();
            $this->assertNotNull($row, 'boardexpert tidak tersimpan');
            $this->putUpdate('/administrator/boardexpert/' . $row->id, ['name' => $unique . ' Upd', 'title' => 'Title2', 'description' => 'desc2'])->assertSessionHasNoErrors();
            $this->assertSame('Title2', DB::table('board_experts')->where('id', $row->id)->value('title'));
            $this->deleteRow('/administrator/boardexpert/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('board_experts')->where('id', $row->id)->first());
        } finally {
            DB::table('board_experts')->where('name', 'like', 'Board Smoke%')->delete();
        }
    }

    public function test_portofolio_crud(): void
    {
        $unique = 'Porto Smoke ' . time();
        try {
            $file = UploadedFile::fake()->image('porto.png', 100, 60);
            $this->postStore('/administrator/portofolio', ['title' => $unique, 'description' => 'desc', 'dates' => '2026', 'attachment' => $file])->assertSessionHasNoErrors();
            $row = DB::table('portofolios')->where('title', $unique)->first();
            $this->assertNotNull($row, 'portofolio tidak tersimpan');
            $this->putUpdate('/administrator/portofolio/' . $row->id, ['title' => $unique . ' Upd', 'description' => 'desc2', 'dates' => '2027', 'attachment' => $file])->assertSessionHasNoErrors();
            $this->assertSame('2027', DB::table('portofolios')->where('id', $row->id)->value('dates'));
            $this->deleteRow('/administrator/portofolio/' . $row->id)->assertSessionHasNoErrors();
            $this->assertNull(DB::table('portofolios')->where('id', $row->id)->first());
        } finally {
            DB::table('portofolios')->where('title', 'like', 'Porto Smoke%')->delete();
        }
    }
}