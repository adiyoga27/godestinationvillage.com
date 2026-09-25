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
    }

    protected function seedPariwisata(): void
    {
        $track = $this->track([
            'name' => 'Pariwisata',
            'slug' => 'pariwisata',
            'tagline' => 'Desa Wisata / Daya Tarik Wisata',
            'description' => 'Identifikasi potensi, strategi pemasaran, dan branding destinasi desa wisata atau daya tarik wisata Anda.',
            'target_audience' => 'Pengelola desa wisata, Pokdarwis, pengelola DTW',
            'estimated_minutes' => 10,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->addQuestions($track, [
            ['Potensi & Atraksi', 'Desa/DTW memiliki atraksi unggulan yang jelas dan berbeda dari destinasi lain.', 'Contoh: alam, budaya, kuliner, atau aktivitas khas yang menjadi magnet utama.'],
            ['Potensi & Atraksi', 'Atraksi utama didukung aktivitas turunan (paket half-day/full-day, workshop, live-in).', 'Wisatawan punya alasan untuk tinggal lebih lama dan belanja lebih banyak.'],
            ['Potensi & Atraksi', 'Potensi dievaluasi berkala dan ada kalender atraksi/event tahunan.', 'Contoh: festival tahunan, musim panen, ritual budaya terjadwal.'],
            ['Aksesibilitas & Amenitas', 'Akses menuju lokasi mudah (jalan, petunjuk arah, transportasi, informasi).', 'Termasuk papan penunjuk, titik kumpul, dan info transportasi publik/swasta.'],
            ['Aksesibilitas & Amenitas', 'Fasilitas dasar tersedia dan terawat (toilet, parkir, tempat ibadah, kuliner, homestay).', 'Standar kebersihan dan keamanan fasilitas dinilai rutin.'],
            ['Aksesibilitas & Amenitas', 'Informasi kunjungan mudah ditemukan online (jam buka, tiket, kontak, peta).', 'Website, Google Maps, dan media sosial aktif dan akurat.'],
            ['Pemasaran & Branding', 'Destinasi memiliki nama/brand dan cerita (storytelling) yang konsisten.', 'Logo, tagline, dan narasi yang dipakai di semua kanal sama.'],
            ['Pemasaran & Branding', 'Ada kanal pemasaran aktif (media sosial, OTA, kemitraan biro perjalanan).', 'Minimal satu kanal utama yang di-update mingguan.'],
            ['Pemasaran & Branding', 'Ulasan wisatawan dipantau dan ditindaklanjuti.', 'Rating Google/TripAdvisor/OTA dibalas dan jadi bahan perbaikan.'],
            ['Kelembagaan & SDM', 'Ada kelembagaan pengelola yang jelas (Pokdarwis/BUMDes/kelompok) dengan AD/ART dan pembagian tugas.', 'Struktur, SK, dan rapat rutin terdokumentasi.'],
            ['Kelembagaan & SDM', 'SDM/pemandu lokal tersertifikasi atau terlatih (pemandu, homestay, kuliner).', 'Pelatihan hospitality, guiding, dan keamanan dasar.'],
            ['Kelembagaan & SDM', 'Keuangan dikelola transparan (pembukuan, bagi hasil ke masyarakat).', 'Ada laporan keuangan berkala yang bisa diakses anggota.'],
            ['Keberlanjutan', 'Ada aturan daya dukung (batas kunjungan, zonasi, kode etik wisatawan).', 'Contoh: kuota harian, zona sakral, larangan plastik sekali pakai.'],
            ['Keberlanjutan', 'Manfaat ekonomi dirasakan warga lokal (tenaga kerja, produk lokal terserap).', 'Minimal 50% belanja operasional ke produk/jasa lokal.'],
            ['Keberlanjutan', 'Limbah dan energi dikelola (sampah terpilah, air, energi terbarukan).', 'Bank sampah, komposting, atau kemitraan pengelolaan limbah.'],
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
            'estimated_minutes' => 10,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $this->addQuestions($track, [
            ['Produk Unggulan', 'Usaha memiliki produk/jasa unggulan yang jelas dan berdaya saing.', 'Satu hero product dengan keunggulan dibanding pesaing.'],
            ['Produk Unggulan', 'Produk memiliki standar mutu konsisten dan kemasan/label yang layak jual.', 'Termasuk PIRT/halal/BPOM bila relevan dan kemasan informatif.'],
            ['Produk Unggulan', 'Ada diferensiasi (varian, kemasan premium, edisi khas desa).', 'Segmentasi harga: reguler, premium, oleh-oleh.'],
            ['Model Bisnis & Kelembagaan', 'Bentuk kelembagaan jelas (BUMDes/UMKM/koperasi) dengan legalitas dasar.', 'NIB, badan hukum koperasi, atau AD/ART BUMDes.'],
            ['Model Bisnis & Kelembagaan', 'Khusus koperasi: RAT berjalan, simpanan/pinjaman tertib, dan anggota aktif berpartisipasi.', 'Indikator kesehatan Koperasi Desa/Kelurahan Merah Putih.'],
            ['Model Bisnis & Kelembagaan', 'Ada pencatatan keuangan dan pemisahan keuangan usaha vs pribadi.', 'Pembukuan sederhana namun rutin dan bisa diaudit.'],
            ['Permodalan & Keuangan', 'Arus kas tercatat dan usaha mampu menutup biaya operasional dari pendapatan sendiri.', 'Tidak bergantung permanen pada bantuan.'],
            ['Permodalan & Keuangan', 'Ada akses permodalan (dana desa, KUR, kemitraan, investor) dan riwayat pembiayaan sehat.', 'Riwayat kredit lancar, proposal bisnis tersedia.'],
            ['Permodalan & Keuangan', 'Ada dana cadangan dan rencana investasi 1–2 tahun ke depan.', 'Peremajaan alat, ekspansi, atau diversifikasi produk.'],
            ['Pemasaran & Akses Pasar', 'Ada saluran penjualan lebih dari satu (offline, online/marketplace, reseller, wisata).', 'Tidak bergantung pada satu pembeli/tengkulak.'],
            ['Pemasaran & Akses Pasar', 'Brand dan promosi berjalan rutin (media sosial, katalog, bazar, kemitraan).', 'Kalender promosi dan materi katalog produk.'],
            ['Pemasaran & Akses Pasar', 'Harga dihitung dari HPP dan ada strategi margin per saluran jual.', 'Tahu BEP dan margin tiap kanal penjualan.'],
            ['SDM & Digitalisasi', 'Ada penanggung jawab tiap fungsi (produksi, keuangan, pemasaran) meski tim kecil.', 'Jobdesk tertulis, bukan semua dikerjakan ketua.'],
            ['SDM & Digitalisasi', 'Transaksi dan administrasi terbantu tools digital (kasir/POS, e-wallet, marketplace).', 'QRIS, pembukuan digital, atau katalog online.'],
            ['SDM & Digitalisasi', 'Anggota/pengelola mengikuti pelatihan minimal 1x setahun.', 'Pelatihan produksi, pemasaran digital, atau manajemen koperasi.'],
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
            'estimated_minutes' => 15,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $pillars = [
            ['Iklim Usaha', 'Perizinan usaha pariwisata di daerah mudah, cepat, dan terintegrasi (OSS/digital).', 'Izin homestay, restoran, biro perjalanan, dan event.'],
            ['Iklim Usaha', 'Ada insentif/kemudahan investasi pariwisata (lahan, pajak daerah, kemitraan).', 'Regulasi dan promosi investasi yang terdokumentasi.'],
            ['Keamanan & Keselamatan', 'Kawasan wisata aman dari kriminalitas dan ada posko/petugas keamanan.', 'Data kejadian dan SOP penanganan darurat.'],
            ['Keamanan & Keselamatan', 'Ada sistem mitigasi bencana dan keselamatan wisatawan (rambu, asuransi, rescue).', 'Peta risiko, jalur evakuasi, tim siaga.'],
            ['Kesehatan & Kebersihan', 'Fasilitas kesehatan mudah dijangkau dari kawasan wisata utama.', 'Puskesmas/klinik/RS rujukan dan nomor darurat.'],
            ['Kesehatan & Kebersihan', 'Sanitasi dan kebersihan kawasan terjaga (TPS, toilet umum, air bersih).', 'Jadwal kebersihan dan audit sanitasi.'],
            ['SDM & Pasar Tenaga Kerja', 'Ketersediaan tenaga pariwisata terampil dan tersertifikasi mencukupi.', 'Pemandu, hospitality, dan ekonomi kreatif bersertifikat.'],
            ['SDM & Pasar Tenaga Kerja', 'Ada program vokasi/pelatihan pariwisata berkelanjutan bersama industri.', 'Politeknik, BLK, atau akademi pariwisata.'],
            ['Kesiapan TIK', 'Konektivitas digital menjangkau kawasan wisata (4G/5G, wifi publik).', 'Cakupan sinyal dan titik wifi gratis.'],
            ['Kesiapan TIK', 'Layanan digital tersedia (tiket online, pembayaran nontunai, informasi digital).', 'E-ticketing, QRIS, dan dashboard data kunjungan.'],
            ['Prioritas Pariwisata', 'Pariwisata menjadi prioritas dokumen perencanaan (RPJMD/Renstra) dengan anggaran memadai.', 'Program dan pagu anggaran pariwisata daerah.'],
            ['Prioritas Pariwisata', 'Ada kelembagaan promosi (BPPD/DMO) dan kalender event daerah.', 'Badan promosi aktif dan CoE/event unggulan.'],
            ['Keterbukaan Internasional', 'Aksesibilitas internasional baik (bandara/pelabuhan, konektivitas, visa).', 'Rute langsung, pelabuhan cruise, fasilitas imigrasi.'],
            ['Keterbukaan Internasional', 'Informasi dan layanan ramah wisman (multibahasa, money changer, tourist info).', 'Signage dan TIC berstandar.'],
            ['Daya Saing Harga', 'Harga jasa wisata kompetitif dan transparan (tiket, parkir, kuliner).', 'Standarisasi tarif dan publikasi harga resmi.'],
            ['Daya Saing Harga', 'Ada variasi segmen harga (backpacker hingga premium) tanpa jebakan harga.', 'Paket berlapis dan pengawasan pungli.'],
            ['Infrastruktur Transportasi Udara', 'Kapasitas dan frekuensi penerbangan ke gerbang kawasan memadai.', 'Slot, maskapai, dan konektivitas antarmoda.'],
            ['Infrastruktur Transportasi Udara', 'Bandara didukung layanan wisatawan (info, transport lanjutan, bagasi).', 'Airport transfer dan tourist helpdesk.'],
            ['Infrastruktur Darat & Pelabuhan', 'Jalan menuju DTW utama mantap dan angkutan umum/darat tersedia.', 'Kondisi jalan, shuttle, dan terminal.'],
            ['Infrastruktur Darat & Pelabuhan', 'Pelabuhan/dermaga wisata aman dan terjadwal (bila relevan).', 'Standar keselamatan penyeberangan.'],
            ['Infrastruktur Layanan Wisatawan', 'Ketersediaan akomodasi, restoran, dan TIC memadai di kawasan utama.', 'Rasio kamar vs kunjungan, sebaran TIC.'],
            ['Infrastruktur Layanan Wisatawan', 'Atraksi didukung fasilitas pengalaman (pusat informasi, suvenir, toilet).', 'Visitor center dan amenitas inti.'],
            ['Sumber Daya Alam', 'Daya tarik alam teridentifikasi, terpetakan, dan terlindungi statusnya.', 'Inventarisasi dan zonasi konservasi.'],
            ['Sumber Daya Alam', 'Ada produk wisata berbasis alam yang dikelola berkelanjutan.', 'Ekowisata dengan kuota daya dukung.'],
            ['Sumber Daya Budaya', 'Aset budaya terinventarisasi (tangible & intangible) dan terpelihara.', 'Cagar budaya, warisan takbenda, maestro.'],
            ['Sumber Daya Budaya', 'Budaya dikemas jadi pengalaman wisata yang menghormati adat.', 'Aturan kunjungan situs sakral/budaya.'],
            ['Sumber Daya Non-Leisure', 'Potensi MICE, edukasi, wellness, dan sport tourism terpetakan.', 'Venue, kampus, fasilitas olahraga.'],
            ['Sumber Daya Non-Leisure', 'Ada event/kunjungan non-leisure rutin yang mendatangkan wisatawan.', 'Konferensi, kompetisi, program edukasi.'],
            ['Keberlanjutan Lingkungan', 'Ada kebijakan dan aksi lingkungan (sampah, emisi, konservasi).', 'Regulasi plastik sekali pakai, PROKLIM, dsb.'],
            ['Keberlanjutan Lingkungan', 'Destinasi/kawasan mengikuti sertifikasi atau standar keberlanjutan.', 'GSTC, CHSE, desa wisata berkelanjutan.'],
            ['Ketahanan Sosial-Ekonomi', 'Manfaat pariwisata terdistribusi ke masyarakat lokal (tenaga kerja, UMKM).', 'Data serapan lokal dan kemitraan.'],
            ['Ketahanan Sosial-Ekonomi', 'Ada perlindungan sosial dan kesiapan krisis ekonomi bagi pelaku wisata.', 'Dana darurat, diversifikasi pasar.'],
            ['Dampak Sosial-Ekonomi Pariwisata', 'Kontribusi pariwisata terhadap PDRB dan PAD terukur dan tumbuh.', 'Neraca pariwisata daerah/satelit.'],
            ['Dampak Sosial-Ekonomi Pariwisata', 'Data kunjungan dan belanja wisatawan dikumpulkan dan dipakai untuk kebijakan.', 'Survei profil dan pola belanja wisatawan.'],
        ];

        // Kelompokkan 2 soal per pilar: dimensi = nama pilar + konteks kawasan.
        $this->addQuestions($track, $pillars);
    }

    protected function seedRegeneratif(): void
    {
        $track = $this->track([
            'name' => 'Seberapa Regeneratif Produkmu?',
            'slug' => 'regeneratif',
            'tagline' => 'Lintas Sektor — Spektrum Ekstraktif → Regeneratif Matang',
            'description' => 'Untuk DTW, desa wisata, hotel, restoran/cafe, biro perjalanan/tour operator, UMKM, dan usaha lain — mengukur posisi usahamu di spektrum ekstraktif menuju regeneratif matang.',
            'target_audience' => 'DTW, desa wisata, hotel, restoran/cafe, tour operator, UMKM',
            'estimated_minutes' => 10,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        $this->addQuestions($track, [
            ['Tata Kelola & Kepemilikan Lokal', 'Kepemilikan dan keputusan usaha dikendalikan warga/komunitas lokal.', 'Bukan sekadar tenaga kerja, tapi pemilik dan pengambil keputusan.'],
            ['Tata Kelola & Kepemilikan Lokal', 'Keuntungan dibagi adil dan transparan ke komunitas (bagi hasil, dana desa, beasiswa).', 'Ada skema benefit-sharing tertulis.'],
            ['Tata Kelola & Kepemilikan Lokal', 'Komunitas punya suara dalam rencana pengembangan (musyawarah/forum).', 'Persetujuan komunitas sebelum ekspansi.'],
            ['Lingkungan & Ekologi', 'Operasional memulihkan alam, bukan sekadar mengurangi kerusakan (tanam, konservasi, restorasi).', 'Regeneratif = meninggalkan alam lebih baik.'],
            ['Lingkungan & Ekologi', 'Jejak karbon, air, dan limbah diukur dan diturunkan sistematis.', 'Audit sederhana + target tahunan.'],
            ['Lingkungan & Ekologi', 'Rantai pasok mengutamakan bahan lokal, musiman, dan rendah kemasan.', 'Pangan lokal, refill, tolak sekali pakai.'],
            ['Sosial & Budaya', 'Budaya lokal dihormati dan dikuatkan (bukan dikomodifikasi merusak).', 'Protokol adat, dress code, pembagian peran adat.'],
            ['Sosial & Budaya', 'Pekerja lokal dibayar adil, terlatih, dan punya jenjang karier.', 'Upah layak, kontrak jelas, pelatihan rutin.'],
            ['Sosial & Budaya', 'Usaha berkontribusi pada ketahanan komunitas (darurat, pendidikan, kesehatan).', 'Dana sosial atau program komunitas.'],
            ['Ekonomi Sirkular', 'Uang wisatawan berputar di ekonomi lokal (belanja lokal, hindari kebocoran).', 'Target % belanja ke pemasok lokal.'],
            ['Ekonomi Sirkular', 'Limbah menjadi sumber daya (kompos, kerajinan, energi).', 'Zero-waste menuju sirkular.'],
            ['Ekonomi Sirkular', 'Kolaborasi antar usaha lokal (paket bersama, koperasi pemasaran).', 'Ekosistem, bukan kompetisi destruktif.'],
            ['Wisatawan & Edukasi', 'Wisatawan diedukasi menjadi penjaga (briefing, interpretasi, partisipasi).', 'Dari konsumen menjadi kontributor.'],
            ['Wisatawan & Edukasi', 'Ada pengalaman partisipatif (tanam, bersih pantai, belajar adat).', 'Hands-on memberi dampak langsung.'],
            ['Wisatawan & Edukasi', 'Dampak kunjungan dikomunikasikan jujur (transparansi, bukan greenwashing).', 'Laporan dampak berkala ke publik.'],
        ]);

        // Catatan: skor regeneratif dibaca sebagai spektrum
        // 0–24 Ekstraktif, 25–49 Transisi, 50–74 Berkembang, 75–100 Regeneratif Matang.
    }
}
