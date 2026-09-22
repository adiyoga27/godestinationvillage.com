<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->string('name', 100);
            $table->string('eyebrow', 191)->nullable();
            $table->string('eyebrow_id', 191)->nullable();
            $table->string('title', 191)->nullable();
            $table->string('title_id', 191)->nullable();
            $table->text('subtitle')->nullable();
            $table->text('subtitle_id')->nullable();
            // Gambar section (about, VR poster). Boleh path asset bawaan
            // (mis. assets/...) atau nama file di storage/homepage-sections/.
            $table->string('image', 191)->nullable();
            $table->string('button_label', 191)->nullable();
            $table->string('button_label_id', 191)->nullable();
            $table->string('button_url', 191)->nullable();
            $table->string('button2_label', 191)->nullable();
            $table->string('button2_label_id', 191)->nullable();
            $table->string('button2_url', 191)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('homepage_sections')->insert([
            [
                'key' => 'stats', 'name' => 'Statistik', 'sort_order' => 1, 'is_active' => true,
                'eyebrow' => null, 'eyebrow_id' => null, 'title' => null, 'title_id' => null,
                'subtitle' => null, 'subtitle_id' => null, 'image' => null,
                'button_label' => null, 'button_label_id' => null, 'button_url' => null,
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'explore_village', 'name' => 'Explore Village', 'sort_order' => 2, 'is_active' => true,
                'eyebrow' => 'Explore Village', 'eyebrow_id' => 'Jelajahi Desa',
                'title' => 'Beautiful Balinese villages, authentic stories',
                'title_id' => 'Desa-desa Bali yang indah, kisah yang autentik',
                'subtitle' => 'Every village has a story. Step into living traditions and meet the communities whose daily lives inspire our tourism experiences.',
                'subtitle_id' => 'Setiap desa punya cerita. Masuki tradisi yang hidup dan temui masyarakat yang kesehariannya menginspirasi pengalaman wisata kami.',
                'image' => null,
                'button_label' => 'View All Villages', 'button_label_id' => 'Lihat Semua Desa',
                'button_url' => 'village',
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'about', 'name' => 'Tentang / Why GODEVI', 'sort_order' => 3, 'is_active' => true,
                'eyebrow' => 'Why GODEVI', 'eyebrow_id' => 'Mengapa GODEVI',
                'title' => "Tourism that gives back to Bali's villages",
                'title_id' => 'Wisata yang memberi kembali untuk desa-desa Bali',
                'subtitle' => 'GODEVI (Go Destination Village) is a socially pro-active business dedicated to uplifting local communities in developing villages through tourism. We create a fair-trade marketplace by empowering village communities — ensuring travel benefits the people who call these places home.',
                'subtitle_id' => 'GODEVI (Go Destination Village) adalah usaha yang proaktif secara sosial dan berdedikasi mengangkat masyarakat lokal di desa berkembang melalui pariwisata.',
                'image' => 'assets/customer/frontdata/images/about.jpg',
                'button_label' => null, 'button_label_id' => null, 'button_url' => null,
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'booklet', 'name' => 'Booklet', 'sort_order' => 4, 'is_active' => true,
                'eyebrow' => 'Company Profile', 'eyebrow_id' => 'Profil Perusahaan',
                'title' => 'Get to know GODEVI through our booklet',
                'title_id' => 'Kenali GODEVI melalui buklet kami',
                'subtitle' => 'Browse our vision, impact and village tourism programs — read online or download the PDF.',
                'subtitle_id' => 'Jelajahi visi, dampak, dan program wisata desa kami — baca online atau unduh PDF.',
                'image' => null,
                'button_label' => null, 'button_label_id' => null, 'button_url' => null,
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'services', 'name' => 'Our Services', 'sort_order' => 5, 'is_active' => true,
                'eyebrow' => 'Our Services', 'eyebrow_id' => 'Layanan Kami',
                'title' => 'Beyond travel — we build thriving villages',
                'title_id' => 'Melampaui perjalanan — kami membangun desa yang maju',
                'subtitle' => null, 'subtitle_id' => null, 'image' => null,
                'button_label' => null, 'button_label_id' => null, 'button_url' => null,
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'virtual_reality', 'name' => 'Virtual Reality', 'sort_order' => 6, 'is_active' => true,
                'eyebrow' => 'Virtual Reality', 'eyebrow_id' => 'Realitas Virtual',
                'title' => 'Witness the wonders of Balinese villages — before you arrive',
                'title_id' => 'Saksikan keajaiban desa-desa Bali — sebelum Anda tiba',
                'subtitle' => "Step into an immersive virtual reality experience that transports you to the fascinating world of Bali's villages. Preview the culture, landscapes and activities that await you.",
                'subtitle_id' => 'Masuki pengalaman realitas virtual yang membawa Anda ke dunia memukau desa-desa Bali.',
                'image' => 'assets/customer/frontdata/images/bg_4.jpg',
                'button_label' => 'Go Virtual', 'button_label_id' => 'Coba Virtual',
                'button_url' => 'https://www.vrfmipa.com/meler',
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'tour_packages', 'name' => 'Tour Packages', 'sort_order' => 7, 'is_active' => true,
                'eyebrow' => 'Tour Packages', 'eyebrow_id' => 'Paket Wisata',
                'title' => "Featured experiences you'll love",
                'title_id' => 'Pengalaman pilihan yang akan Anda sukai',
                'subtitle' => 'Handpicked village tours and activities curated for authentic cultural immersion.',
                'subtitle_id' => 'Tur desa dan aktivitas pilihan yang dikurasi untuk perendaman budaya autentik.',
                'image' => null,
                'button_label' => 'See All Packages', 'button_label_id' => 'Lihat Semua Paket',
                'button_url' => 'tour-packages',
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'events', 'name' => 'Events', 'sort_order' => 8, 'is_active' => true,
                'eyebrow' => 'Events', 'eyebrow_id' => 'Acara',
                'title' => 'Upcoming village events',
                'title_id' => 'Acara desa mendatang',
                'subtitle' => 'Festivals, workshops and cultural celebrations hosted by our villages.',
                'subtitle_id' => 'Festival, lokakarya, dan perayaan budaya yang diselenggarakan desa-desa kami.',
                'image' => null,
                'button_label' => 'See All Events', 'button_label_id' => 'Lihat Semua Acara',
                'button_url' => 'events',
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'homestay', 'name' => 'Home Stay', 'sort_order' => 9, 'is_active' => true,
                'eyebrow' => 'Home Stay', 'eyebrow_id' => 'Homestay',
                'title' => 'Stay with local families',
                'title_id' => 'Menginap bersama keluarga lokal',
                'subtitle' => 'Comfortable village homestays where you live the local way, hosted with Balinese warmth.',
                'subtitle_id' => 'Homestay desa yang nyaman, rasakan keseharian lokal dengan keramahan khas Bali.',
                'image' => null,
                'button_label' => 'See All Home Stays', 'button_label_id' => 'Lihat Semua Homestay',
                'button_url' => 'homestay',
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'news', 'name' => 'News', 'sort_order' => 10, 'is_active' => true,
                'eyebrow' => 'News & Insights', 'eyebrow_id' => 'Berita & Wawasan',
                'title' => 'Stories from the villages',
                'title_id' => 'Cerita dari desa-desa',
                'subtitle' => null, 'subtitle_id' => null, 'image' => null,
                'button_label' => 'All Articles', 'button_label_id' => 'Semua Artikel',
                'button_url' => 'news',
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'testimonials', 'name' => 'Testimoni', 'sort_order' => 11, 'is_active' => true,
                'eyebrow' => 'Testimonials', 'eyebrow_id' => 'Testimoni',
                'title' => 'What our travelers say',
                'title_id' => 'Kata para wisatawan kami',
                'subtitle' => null, 'subtitle_id' => null, 'image' => null,
                'button_label' => null, 'button_label_id' => null, 'button_url' => null,
                'button2_label' => null, 'button2_label_id' => null, 'button2_url' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'key' => 'cta', 'name' => 'CTA / Contact', 'sort_order' => 12, 'is_active' => true,
                'eyebrow' => null, 'eyebrow_id' => null,
                'title' => 'Ready for an authentic village experience?',
                'title_id' => 'Siap untuk pengalaman desa yang autentik?',
                'subtitle' => 'Book your tour, homestay or event today and support the communities that make Bali extraordinary.',
                'subtitle_id' => 'Pesan tur, homestay, atau acara Anda hari ini dan dukung masyarakat yang membuat Bali luar biasa.',
                'image' => null,
                'button_label' => 'Browse Experiences', 'button_label_id' => 'Jelajahi Pengalaman',
                'button_url' => 'tour-packages',
                'button2_label' => 'Contact Us', 'button2_label_id' => 'Hubungi Kami',
                'button2_url' => 'contact',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
