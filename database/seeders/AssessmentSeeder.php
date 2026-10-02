<?php

namespace Database\Seeders;

use App\Models\AssessmentQuestion;
use App\Models\AssessmentTrack;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPariwisata();
        $this->seedEkonomiDesa();
        $this->seedDayaSaing();
        $this->seedRegeneratif();
    }

    protected function track(array $attrs): AssessmentTrack
    {
        // Harga awal per Brief §5 (admin dapat mengubah tanpa deploy).
        $prices = [
            'pariwisata' => 199000,
            'ekonomi-desa' => 199000,
            'regeneratif' => 299000,
            'daya-saing-destinasi' => 499000,
        ];
        $attrs['price'] = $prices[$attrs['slug']] ?? 199000;

        return AssessmentTrack::updateOrCreate(['slug' => $attrs['slug']], $attrs);
    }

    protected function addQuestions(AssessmentTrack $track, array $rows): void
    {
        $order = 1;
        foreach ($rows as [$dimension, $question, $help]) {
            AssessmentQuestion::updateOrCreate(
                ['track_id' => $track->id, 'question' => $question],
                [
                    'dimension' => $dimension,
                    'help_text' => $help,
                    'weight' => 1,
                    'sort_order' => $order++,
                    'is_active' => true,
                ]
            );
        }

        // Nonaktifkan pernyataan lama yang tidak lagi dipakai (hasil lama tetap menyimpan salinannya).
        AssessmentQuestion::where('track_id', $track->id)
            ->whereNotIn('question', array_column($rows, 1))
            ->update(['is_active' => false]);
    }

    protected function seedPariwisata(): void
    {
        $track = $this->track([
            'name' => 'Pariwisata',
            'slug' => 'pariwisata',
            'tagline' => 'Desa Wisata / Daya Tarik Wisata',
            'description' => 'Identifikasi potensi, strategi pemasaran, dan branding destinasi desa wisata atau daya tarik wisata Anda.',
            'target_audience' => 'Pengelola desa wisata, Pokdarwis, pengelola DTW',
            'estimated_minutes' => 5,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Satu pernyataan per dimensi; bobot di AssessmentService::DIMENSION_WEIGHTS (Brief §3.1).
        $this->addQuestions($track, [
            ['Daya Tarik Alam & Budaya', 'Daya Tarik Alam & Budaya', 'Keunikan lanskap, situs budaya, tradisi, kesenian, atau kuliner khas yang menjadi daya tarik utama.'],
            ['Aksesibilitas & Infrastruktur', 'Aksesibilitas & Infrastruktur', 'Kondisi jalan, jarak dari pusat kota/bandara, transportasi umum, listrik, air bersih, sinyal komunikasi.'],
            ['Amenitas & Akomodasi', 'Amenitas & Akomodasi', 'Ketersediaan homestay, rumah makan, toilet umum, area parkir, dan penunjuk arah.'],
            ['Kesiapan Komunitas & SDM', 'Kesiapan Komunitas & SDM', 'Kapasitas pemandu lokal, Kelompok Sadar Wisata (Pokdarwis), keramahan, kemampuan bahasa asing.'],
            ['Tata Kelola & Kelembagaan', 'Tata Kelola & Kelembagaan', 'Keberadaan BUMDes/Pokdarwis aktif, pembagian manfaat ekonomi, aturan desa terkait wisata.'],
            ['Kehadiran Digital Saat Ini', 'Kehadiran Digital Saat Ini', 'Google Maps/Business, media sosial aktif, ulasan daring, sistem reservasi atau pembayaran digital.'],
        ]);
    }

    protected function seedEkonomiDesa(): void
    {
        $track = $this->track([
            'name' => 'Ekonomi Desa',
            'slug' => 'ekonomi-desa',
            'tagline' => 'Usaha Desa, UMKM & Koperasi',
            'description' => 'Termasuk Koperasi Desa/Kelurahan Merah Putih — identifikasi produk unggulan, model bisnis, dan strategi pasar usaha desa Anda.',
            'target_audience' => 'BUMDes, UMKM desa, pengurus Koperasi Desa/Kelurahan Merah Putih',
            'estimated_minutes' => 7,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Satu pernyataan per dimensi; bobot di AssessmentService::DIMENSION_WEIGHTS.
        $this->addQuestions($track, [
            ['Kejelasan Produk/Jasa Unggulan', 'Kejelasan Produk/Jasa Unggulan', 'Apakah usaha/koperasi sudah punya produk atau jasa unggulan yang jelas dan konsisten diproduksi? Ini titik lemah paling umum pada Koperasi Desa/Kelurahan Merah Putih yang baru terbentuk.'],
            ['Kapasitas Produksi & Bahan Baku', 'Kapasitas Produksi & Bahan Baku', 'Ketersediaan bahan baku lokal, kapasitas dan konsistensi produksi, standar kualitas.'],
            ['Legalitas & Kelembagaan', 'Legalitas & Kelembagaan', 'Akta pendirian/NIB, struktur pengurus, Rapat Anggota Tahunan (RAT) untuk koperasi, sertifikasi (halal/PIRT/BPOM).'],
            ['Akses Pasar & Jaringan Distribusi', 'Akses Pasar & Jaringan Distribusi', 'Jangkauan pasar saat ini (lokal/regional/nasional), mitra distribusi, keterlibatan dalam rantai pasok yang lebih besar.'],
            ['Kehadiran Digital & Branding Usaha', 'Kehadiran Digital & Branding Usaha', 'Katalog produk, media sosial usaha, marketplace, kemasan dan identitas merek.'],
            ['Akses Permodalan & Kemitraan', 'Akses Permodalan & Kemitraan', 'Akses ke modal kerja, pembiayaan koperasi/perbankan, kemitraan dengan offtaker atau investor.'],
            ['Kapasitas SDM & Manajemen Usaha', 'Kapasitas SDM & Manajemen Usaha', 'Kemampuan pengurus/anggota dalam pembukuan, manajemen operasional, dan pengambilan keputusan usaha.'],
        ]);
    }

    protected function seedDayaSaing(): void
    {
        $track = $this->track([
            'name' => 'Kualitas & Daya Saing Destinasi',
            'slug' => 'daya-saing-destinasi',
            'tagline' => 'Untuk Pemda & Institusi — 17 Pilar TTDI',
            'description' => 'Mengikuti seluruh 17 pilar resmi Travel & Tourism Development Index (TTDI) World Economic Forum, diadaptasi ke skala kawasan/kabupaten.',
            'target_audience' => 'Pemda, dinas pariwisata, Bappeda, institusi kawasan',
            'estimated_minutes' => 10,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        // 17 pilar TTDI, satu pernyataan per pilar; subindeks & bobot di AssessmentService.
        $this->addQuestions($track, [
            ['Lingkungan Usaha (Business Environment)', 'Lingkungan Usaha (Business Environment)', 'Kemudahan berusaha di sektor pariwisata pada level daerah — perizinan usaha wisata, insentif investasi, birokrasi pelaku usaha.'],
            ['Keamanan & Keselamatan (Safety and Security)', 'Keamanan & Keselamatan (Safety and Security)', 'Tingkat keamanan bagi wisatawan dan pelaku usaha, riwayat insiden, sistem pengamanan kawasan.'],
            ['Kesehatan & Higienitas (Health and Hygiene)', 'Kesehatan & Higienitas (Health and Hygiene)', 'Akses fasilitas kesehatan, standar higiene di destinasi, kesiapan tanggap darurat kesehatan.'],
            ['SDM & Pasar Tenaga Kerja (Human Resources and Labour Market)', 'SDM & Pasar Tenaga Kerja (Human Resources and Labour Market)', 'Ketersediaan tenaga kerja terampil pariwisata, lembaga pelatihan, kualitas dan perlindungan tenaga kerja lokal.'],
            ['Kesiapan ICT (ICT Readiness)', 'Kesiapan ICT (ICT Readiness)', 'Konektivitas internet, adopsi teknologi digital oleh pelaku usaha dan pemerintah daerah setempat.'],
            ['Prioritas Kebijakan Pariwisata (Prioritization of T&T)', 'Prioritas Kebijakan Pariwisata (Prioritization of T&T)', 'Sejauh mana pariwisata menjadi prioritas dalam RPJMD/RIPPARDA daerah, alokasi anggaran, dan dukungan politik.'],
            ['Keterbukaan Internasional (International Openness)', 'Keterbukaan Internasional (International Openness)', 'Kemudahan akses bagi wisatawan mancanegara — promosi internasional, kemitraan lintas negara (adaptasi lokal dari indikator kebijakan visa nasional TTDI).'],
            ['Daya Saing Harga (Price Competitiveness)', 'Daya Saing Harga (Price Competitiveness)', 'Kewajaran harga akomodasi, layanan, dan aktivitas wisata dibanding destinasi pembanding.'],
            ['Infrastruktur Transportasi Udara (Air Transport Infrastructure)', 'Infrastruktur Transportasi Udara (Air Transport Infrastructure)', 'Akses dan kualitas bandara terdekat, frekuensi penerbangan yang melayani kawasan ini.'],
            ['Infrastruktur Darat & Pelabuhan (Ground and Port Infrastructure)', 'Infrastruktur Darat & Pelabuhan (Ground and Port Infrastructure)', 'Kualitas jalan menuju kawasan, transportasi umum, pelabuhan/dermaga bila relevan.'],
            ['Infrastruktur Layanan Wisatawan (Tourist Service Infrastructure)', 'Infrastruktur Layanan Wisatawan (Tourist Service Infrastructure)', 'Ketersediaan akomodasi, penyewaan kendaraan, ATM/perbankan, pusat informasi wisata.'],
            ['Sumber Daya Alam (Natural Resources)', 'Sumber Daya Alam (Natural Resources)', 'Kekayaan lanskap, kawasan lindung, keragaman hayati yang menjadi daya tarik wisata alam.'],
            ['Sumber Daya Budaya (Cultural Resources)', 'Sumber Daya Budaya (Cultural Resources)', 'Situs budaya/sejarah, tradisi hidup, kekayaan kuliner dan kesenian lokal.'],
            ['Sumber Daya Non-Wisata Santai (Non-Leisure Resources)', 'Sumber Daya Non-Wisata Santai (Non-Leisure Resources)', 'Potensi kawasan untuk MICE (rapat/insentif/konvensi/pameran), wisata minat khusus, atau kunjungan bisnis.'],
            ['Keberlanjutan Lingkungan (Environmental Sustainability)', 'Keberlanjutan Lingkungan (Environmental Sustainability)', 'Pengelolaan sampah, kualitas udara/air, upaya mitigasi perubahan iklim dan konservasi kawasan.'],
            ['Ketahanan Sosial-Ekonomi (Socioeconomic Resilience and Conditions)', 'Ketahanan Sosial-Ekonomi (Socioeconomic Resilience and Conditions)', 'Pemerataan manfaat ekonomi pariwisata, kesetaraan dalam angkatan kerja, jaring pengaman sosial masyarakat lokal.'],
            ['Tekanan & Dampak Permintaan Wisata (T&T Demand Pressure and Impact)', 'Tekanan & Dampak Permintaan Wisata (T&T Demand Pressure and Impact)', 'Tingkat tekanan kunjungan terhadap daya dukung kawasan (overtourism), dampak terhadap sumber daya lokal.'],
        ]);
    }

    protected function seedRegeneratif(): void
    {
        $track = $this->track([
            'name' => 'Seberapa Regeneratif Produkmu?',
            'slug' => 'regeneratif',
            'tagline' => 'Lintas Sektor — Spektrum Ekstraktif → Regeneratif Matang',
            'description' => 'Untuk DTW, desa wisata, hotel, restoran/cafe, biro perjalanan/tour operator, UMKM, dan usaha lain — mengukur posisi usahamu di spektrum ekstraktif menuju regeneratif matang.',
            'target_audience' => 'DTW, desa wisata, hotel, restoran/cafe, tour operator, UMKM',
            'estimated_minutes' => 7,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // Satu pernyataan per dimensi; bobot di AssessmentService::DIMENSION_WEIGHTS.
        $this->addQuestions($track, [
            ['Dampak Positif terhadap Ekosistem', 'Dampak Positif terhadap Ekosistem', 'Sejauh mana operasional aktif memulihkan lingkungan (restorasi, konservasi, penanaman) — bukan sekadar mengurangi dampak negatif.'],
            ['Sirkularitas & Pengelolaan Sumber Daya', 'Sirkularitas & Pengelolaan Sumber Daya', 'Pengelolaan sampah, penggunaan ulang material, efisiensi energi/air, pengurangan single-use plastic.'],
            ['Pemberdayaan & Kepemilikan Komunitas Lokal', 'Pemberdayaan & Kepemilikan Komunitas Lokal', 'Sejauh mana masyarakat lokal bukan hanya menerima manfaat, tapi memiliki peran dan kepemilikan nyata dalam operasional/keputusan.'],
            ['Keaslian & Pelestarian Budaya', 'Keaslian & Pelestarian Budaya', 'Integrasi budaya/tradisi lokal yang hidup dan otentik, bukan sekadar dekorasi atau pertunjukan seremonial.'],
            ['Rantai Pasok & Sirkulasi Ekonomi Lokal', 'Rantai Pasok & Sirkulasi Ekonomi Lokal', 'Proporsi bahan baku, tenaga kerja, dan mitra usaha yang bersumber dari sekitar lokasi — mengurangi kebocoran ekonomi ke luar daerah.'],
            ['Edukasi & Transformasi Pengunjung/Konsumen', 'Edukasi & Transformasi Pengunjung/Konsumen', 'Sejauh mana pengunjung/konsumen diajak memahami dan terlibat aktif dalam nilai regeneratif, bukan hanya mengonsumsi pasif.'],
            ['Tata Kelola Adaptif & Pembelajaran Berkelanjutan', 'Tata Kelola Adaptif & Pembelajaran Berkelanjutan', 'Ada tidaknya mekanisme monitoring, evaluasi, dan penyesuaian praktik secara berkala berdasarkan dampak nyata.'],
        ]);

        // Catatan: skor regeneratif dibaca sebagai spektrum
        // 0–24 Ekstraktif, 25–49 Transisi, 50–74 Berkembang, 75–100 Regeneratif Matang.
    }
}
