<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('name', 100);
            $table->string('title', 191)->nullable();
            $table->string('title_id', 191)->nullable();
            $table->text('subtitle')->nullable();
            $table->text('subtitle_id')->nullable();
            // Gambar latar: boleh path asset bawaan (assets/...) atau
            // nama file di storage/page-heroes/.
            $table->string('image', 191)->nullable();
            $table->timestamps();
        });

        // Nilai awal = teks & gambar yang selama ini hardcoded di tiap halaman,
        // agar tampilan tidak berubah dan tinggal diubah dari admin.
        $now = now();
        DB::table('page_heroes')->insert([
            ['key' => 'homestay', 'name' => 'Homestay', 'title' => 'Bali Homestay & Village Stay', 'title_id' => null, 'subtitle' => 'Wake up to village life — stay with local families and experience genuine Balinese hospitality.', 'subtitle_id' => null, 'image' => 'assets/customer/frontdata/images/bg_1.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'village', 'name' => 'Explore Village', 'title' => 'Explore Villages in Bali', 'title_id' => null, 'subtitle' => 'Discover authentic Balinese villages, culture and community-driven tourism experiences curated by GODEVI.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/explorer.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'tour-packages', 'name' => 'Tour Packages', 'title' => 'Bali Tour Packages', 'title_id' => null, 'subtitle' => 'Curated village experiences, cultural immersion and unforgettable adventures designed with local communities.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/bestoffer.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'events', 'name' => 'Events', 'title' => 'Village Events & Festivals', 'title_id' => null, 'subtitle' => 'Join authentic village ceremonies, workshops and community events across Bali.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/header-event.png', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'news', 'name' => 'News', 'title' => 'News & Insights', 'title_id' => null, 'subtitle' => 'Stories, updates and insights about sustainable village tourism and community empowerment in Bali.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/blog-style3.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'contact', 'name' => 'Contact Us', 'title' => 'Contact Us', 'title_id' => null, 'subtitle' => 'Planning a village escape or a homestay stay? Our team is here to help — reach out any time.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/explorer.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'faq', 'name' => 'FAQ', 'title' => 'Frequently Asked Questions', 'title_id' => null, 'subtitle' => 'Everything you need to know about GODEVI, booking, payment and cancellations.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/faq.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'services', 'name' => 'Our Services', 'title' => 'Our Services', 'title_id' => null, 'subtitle' => 'From tourism planning to destination branding — we help villages thrive through responsible tourism.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/services.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'company-profile', 'name' => 'Company Profile', 'title' => 'Company Profile', 'title_id' => null, 'subtitle' => 'GODEVI (PT Banua Wisata Lestari) — dedicated to socially responsible and sustainable village tourism in Bali.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/explorer.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'founding', 'name' => 'The Founding', 'title' => 'The Founding', 'title_id' => null, 'subtitle' => 'The story of GODEVI — a socially pro-active business built to uplift village communities in Bali.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/founding-timenile.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'ourteam', 'name' => 'Our Team', 'title' => 'Our Team', 'title_id' => null, 'subtitle' => 'Meet the passionate people behind GODEVI — dedicated to uplifting communities through responsible tourism.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/our-team-timenile.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'boardexpert', 'name' => 'Board of Experts', 'title' => 'Board of Experts', 'title_id' => null, 'subtitle' => 'The advisors guiding GODEVI in sustainable tourism, community development and destination management.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/team.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'portofolio', 'name' => 'Portfolio', 'title' => 'Our Portfolio', 'title_id' => null, 'subtitle' => 'Village tourism projects, community empowerment programs and sustainable tourism initiatives across Bali.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/founding-timenile.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'ourpartner', 'name' => 'Our Partners', 'title' => 'Our Partners', 'title_id' => null, 'subtitle' => 'The partners and collaborators supporting GODEVI in building sustainable village tourism communities across Bali.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/partner.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'terms', 'name' => 'Terms', 'title' => 'Terms & Conditions', 'title_id' => null, 'subtitle' => 'Please read these terms of use carefully before using GODEVI services.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/terms.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'search', 'name' => 'Search', 'title' => 'Search Results', 'title_id' => null, 'subtitle' => null, 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/explorer.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'account', 'name' => 'My Account', 'title' => 'My Account', 'title_id' => null, 'subtitle' => 'Update your profile details and keep your information up to date.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/account.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'unsubscribe', 'name' => 'Unsubscribe', 'title' => 'Unsubscribe Newsletter', 'title_id' => null, 'subtitle' => 'Manage your newsletter subscription on GODEVI.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/news.jpg', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'certificate', 'name' => 'Certificate', 'title' => 'Certificate Details', 'title_id' => null, 'subtitle' => 'View and download your official GODEVI certificate.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/surat-sertif-header.png', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'delete-account', 'name' => 'Delete Account', 'title' => 'Delete Account', 'title_id' => null, 'subtitle' => 'Learn how to request the deletion of your GODEVI account.', 'subtitle_id' => null, 'image' => 'assets/customer/img/page-title-area/privacy.jpg', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('page_heroes');
    }
};
