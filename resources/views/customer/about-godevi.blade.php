@extends('customer/layout')

@section('content')

<x-partials.page-hero
    page="about-godevi"
    title="Tentang GODEVI"
    subtitle="Unit bisnis PT Banua Wisata Lestari — riset & konsultansi pengembangan desa dan destinasi pariwisata sejak 2018."
    image="assets/customer/frontdata/images/bg_2.jpg"
    :crumbs="['Beranda' => '/', 'Tentang GODEVI' => '']"
/>

{{-- ============ INTRO ============ --}}
<section class="section-pad">
    <div class="container-gd">
        <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
            <div data-vue="Reveal">
                <p class="eyebrow">Go Destination Village</p>
                <h2 class="font-display text-3xl font-bold leading-tight text-ink-950 sm:text-4xl lg:text-[2.75rem]">
                    Desa adalah potensi unik yang layak diperkenalkan ke dunia.
                </h2>
                <p class="mt-6 leading-relaxed text-ink-600">
                    <strong class="text-ink-900">GODEVI</strong> adalah unit bisnis
                    <strong class="text-ink-900">PT Banua Wisata Lestari</strong> yang bergerak di bidang
                    riset dan konsultansi pengembangan desa serta destinasi pariwisata. Sejak
                    <strong class="text-ink-900">2018</strong>, GODEVI telah mendampingi lebih dari
                    <strong class="text-ink-900">50 desa wisata di Bali dan Indonesia Timur</strong>, dengan
                    pendekatan yang memadukan riset ilmiah, penguatan ekonomi desa dan koperasi,
                    strategi tata kelola destinasi, hingga komersialisasi produk dan pengalaman wisata secara nyata.
                </p>
                <p class="mt-4 leading-relaxed text-ink-600">
                    Kerja GODEVI dijalankan lewat <strong class="text-ink-900">empat pilar layanan yang saling menopang</strong> —
                    mulai dari bukti hingga transaksi nyata di pasar.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#pilar" class="btn btn-primary">Kenali 4 Pilar Kami</a>
                    <a href="{{ url('contact') }}" class="btn btn-secondary">Hubungi Tim Riset</a>
                </div>
            </div>

            <div class="relative" data-vue="Reveal" data-props='{"delay":120}'>
                <div class="overflow-hidden rounded-[2rem] shadow-lift">
                    <img src="{{ asset('assets/customer/frontdata/images/about.jpg') }}"
                        alt="Pendampingan desa wisata oleh GODEVI"
                        class="h-[420px] w-full object-cover lg:h-[520px]" loading="lazy">
                </div>
                {{-- Floating badge --}}
                <div class="absolute -bottom-6 left-6 right-6 sm:left-8 sm:right-auto">
                    <div class="flex items-center gap-4 rounded-2xl border border-ink-100 bg-white/95 px-5 py-4 shadow-lift backdrop-blur">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-forest-600 text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                        </span>
                        <div>
                            <p class="font-display text-lg font-bold leading-none text-ink-950">Berbasis Data,</p>
                            <p class="mt-1 text-sm text-ink-500">bukan asumsi — regeneratif, bukan eksploitasi.</p>
                        </div>
                    </div>
                </div>
                {{-- Deco --}}
                <div class="pointer-events-none absolute -right-6 -top-6 -z-10 h-40 w-40 rounded-[2rem] bg-brand-50"></div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="mt-16 grid grid-cols-2 gap-4 lg:grid-cols-4" data-vue="Reveal">
            <div class="card p-6 text-center">
                <p class="font-display text-4xl font-bold text-brand-600">2018</p>
                <p class="mt-2 text-sm font-semibold text-ink-500">Mendampingi desa<br>sejak tahun</p>
            </div>
            <div class="card p-6 text-center">
                <p class="font-display text-4xl font-bold text-brand-600">50+</p>
                <p class="mt-2 text-sm font-semibold text-ink-500">Desa wisata<br>terdampingi</p>
            </div>
            <div class="card p-6 text-center">
                <p class="font-display text-xl font-bold leading-tight text-brand-600 sm:text-2xl">Bali &<br>Indonesia Timur</p>
                <p class="mt-2 text-sm font-semibold text-ink-500">Wilayah kerja</p>
            </div>
            <div class="card p-6 text-center">
                <p class="font-display text-4xl font-bold text-brand-600">4</p>
                <p class="mt-2 text-sm font-semibold text-ink-500">Pilar layanan<br>terintegrasi</p>
            </div>
        </div>
    </div>
</section>

{{-- ============ 4 PILAR ============ --}}
<section id="pilar" class="section-pad bg-cream-50">
    <div class="container-gd">
        <div class="mx-auto mb-12 max-w-2xl text-center" data-vue="Reveal">
            <p class="eyebrow justify-center">Cara Kami Bekerja</p>
            <h2 class="font-display text-3xl font-bold text-ink-950 sm:text-4xl">Empat pilar, satu alur: dari bukti hingga transaksi</h2>
            <p class="mt-4 text-ink-600">Setiap pendampingan desa bergerak dalam satu rangkaian utuh — dikaji secara ilmiah, dikuatkan ekonominya, ditata kelolanya, lalu dikomersialisasikan secara nyata.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div data-vue="Reveal" class="card card-hover flex flex-col p-7">
                <span class="badge bg-brand-50 text-brand-600">Pilar 01</span>
                <span class="mt-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" /></svg>
                </span>
                <h3 class="mt-5 font-display text-lg font-bold leading-snug text-ink-950">Research &amp; Scientific Consulting</h3>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-ink-600">Riset ilmiah sebagai fondasi — kajian potensi, data sosial-ekonomi, dan bukti akademik sebelum setiap keputusan.</p>
            </div>
            <div data-vue="Reveal" data-props='{"delay":80}' class="card card-hover flex flex-col p-7">
                <span class="badge bg-forest-50 text-forest-700">Pilar 02</span>
                <span class="mt-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-forest-600 text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                </span>
                <h3 class="mt-5 font-display text-lg font-bold leading-snug text-ink-950">Rural Economic &amp; Cooperative Development</h3>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-ink-600">Penguatan ekonomi desa dan koperasi — memastikan manfaat wisata tinggal dan berputar di desa.</p>
            </div>
            <div data-vue="Reveal" data-props='{"delay":160}' class="card card-hover flex flex-col p-7">
                <span class="badge bg-brand-50 text-brand-600">Pilar 03</span>
                <span class="mt-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-ink-950 text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m21 3a9 9 0 11-18 0 9 9 0 0118 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z" /></svg>
                </span>
                <h3 class="mt-5 font-display text-lg font-bold leading-snug text-ink-950">Tourism Advisory &amp; Destination Governance</h3>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-ink-600">Strategi dan tata kelola destinasi — kebijakan, kelembagaan, dan standar pengelolaan yang berkelanjutan.</p>
            </div>
            <div data-vue="Reveal" data-props='{"delay":240}' class="card card-hover flex flex-col p-7">
                <span class="badge bg-forest-50 text-forest-700">Pilar 04 · Muara</span>
                <span class="mt-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </span>
                <h3 class="mt-5 font-display text-lg font-bold leading-snug text-ink-950">Destination &amp; Product Commercialization</h3>
                <p class="mt-2 flex-1 text-sm leading-relaxed text-ink-600">Komersialisasi produk dan pengalaman wisata — muara seluruh kerja: transaksi nyata di pasar.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============ KEYAKINAN / PENDEKATAN ============ --}}
<section class="relative overflow-hidden bg-ink-950 section-pad">
    <div class="pointer-events-none absolute -left-24 top-1/2 h-96 w-96 -translate-y-1/2 rounded-full bg-forest-600/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-brand-600/20 blur-3xl"></div>
    <div class="container-gd relative">
        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">
            <div data-vue="Reveal">
                <p class="eyebrow !text-brand-400">Keyakinan Kami</p>
                <h2 class="font-display text-3xl font-bold leading-tight text-white sm:text-4xl">Berangkat dari data, berorientasi pada pemulihan.</h2>
                <p class="mt-5 leading-relaxed text-white/70">
                    GODEVI meyakini bahwa desa adalah ruang berkumpulnya berbagai potensi yang unik dan layak
                    diperkenalkan ke dunia — namun pengembangannya harus berangkat dari
                    <strong class="text-white">data, bukan asumsi</strong>, dan berorientasi pada
                    <strong class="text-white">pemulihan (regeneratif), bukan sekadar eksploitasi</strong>.
                </p>
                <p class="mt-4 leading-relaxed text-white/70">
                    Pendekatan ini didukung oleh kepakaran akademis tim GODEVI di bidang pariwisata regeneratif,
                    tata kelola destinasi, dan ketahanan pariwisata — yang telah dituangkan dalam
                    publikasi ilmiah terindeks dan buku referensi.
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2" data-vue="Reveal" data-props='{"delay":120}'>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                    <p class="font-display text-2xl font-bold text-white">Data</p>
                    <p class="mt-1 text-sm font-semibold uppercase tracking-wider text-brand-400">bukan asumsi</p>
                    <p class="mt-3 text-sm leading-relaxed text-white/60">Setiap rekomendasi desa lahir dari riset dan bukti lapangan yang dapat dipertanggungjawabkan.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                    <p class="font-display text-2xl font-bold text-white">Regeneratif</p>
                    <p class="mt-1 text-sm font-semibold uppercase tracking-wider text-forest-300">bukan eksploitasi</p>
                    <p class="mt-3 text-sm leading-relaxed text-white/60">Pariwisata harus memulihkan alam, budaya, dan ekonomi desa — bukan mengurasnya.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur sm:col-span-2">
                    <div class="flex items-start gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-brand-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                        </span>
                        <p class="text-sm leading-relaxed text-white/70"><strong class="text-white">Dukungan akademis:</strong> pariwisata regeneratif, tata kelola destinasi, dan ketahanan pariwisata — terdokumentasi dalam publikasi ilmiah terindeks dan buku referensi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ FILOSOFI & IDENTITAS ============ --}}
<section class="section-pad">
    <div class="container-gd">
        <div class="mx-auto mb-12 max-w-2xl text-center" data-vue="Reveal">
            <p class="eyebrow justify-center">Filosofi &amp; Identitas</p>
            <h2 class="font-display text-3xl font-bold text-ink-950 sm:text-4xl">Go Destination Village</h2>
            <p class="mt-4 text-ink-600">Mengantarkan desa menjadi destinasi yang dikenal dunia — tanpa kehilangan jati dirinya.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div data-vue="Reveal" class="card card-hover p-8">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                </span>
                <h3 class="mt-5 font-display text-xl font-bold text-ink-950">Nama &amp; Misi</h3>
                <p class="mt-3 text-sm leading-relaxed text-ink-600"><strong class="text-ink-900">GODEVI — Go Destination Village</strong> mencerminkan misi mengantarkan desa menjadi destinasi yang dikenal dunia tanpa kehilangan jati dirinya.</p>
            </div>
            <div data-vue="Reveal" data-props='{"delay":100}' class="card card-hover p-8">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-forest-50 text-forest-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" /></svg>
                </span>
                <h3 class="mt-5 font-display text-xl font-bold text-ink-950">Terinspirasi Jalak Bali</h3>
                <p class="mt-3 text-sm leading-relaxed text-ink-600">Logo GODEVI terinspirasi dari <strong class="text-ink-900">Jalak Bali</strong> — satwa langka dan unik, simbol potensi alam yang layak dijaga sekaligus diperkenalkan ke dunia.</p>
            </div>
            <div data-vue="Reveal" data-props='{"delay":200}' class="card card-hover p-8">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cream-100 text-ink-800">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" /></svg>
                </span>
                <h3 class="mt-5 font-display text-xl font-bold text-ink-950">Warna yang Bercerita</h3>
                <p class="mt-3 text-sm leading-relaxed text-ink-600"><strong class="text-ink-900">Warna-warna ceria</strong> merepresentasikan semangat pariwisata yang penuh pengalaman menggembirakan. <strong class="text-forest-700">Hijau pada Jalak Bali</strong> menegaskan orientasi GODEVI sebagai bisnis berbasis riset dan digital yang berpihak pada kelestarian lingkungan.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============ SEE ============ --}}
<section class="section-pad bg-cream-50">
    <div class="container-gd">
        <div class="overflow-hidden rounded-[2rem] bg-ink-950" data-vue="Reveal">
            <div class="grid lg:grid-cols-5">
                <div class="relative flex flex-col justify-center p-8 sm:p-12 lg:col-span-2 lg:p-14">
                    <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full bg-brand-600/20 blur-3xl"></div>
                    <p class="eyebrow !text-brand-400">Prinsip SEE</p>
                    <p class="font-display text-5xl font-bold tracking-tight text-white sm:text-6xl">SEE</p>
                    <p class="mt-2 text-sm font-bold uppercase tracking-[0.2em] text-white/60">Sustainability · Empowerment · Entrepreneurship</p>
                    <p class="mt-5 leading-relaxed text-white/70">Semangat SEE kini tercermin langsung dalam keempat pilar layanan GODEVI — dari kajian hingga transaksi.</p>
                </div>
                <div class="grid gap-4 bg-white p-8 sm:p-10 lg:col-span-3 lg:p-12">
                    <div class="flex gap-4 rounded-2xl border border-ink-100 bg-cream-50 p-5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-forest-600 font-display text-lg font-bold text-white">S</span>
                        <div>
                            <h3 class="font-bold text-ink-950">Sustainability — Keberlanjutan</h3>
                            <p class="mt-1 text-sm leading-relaxed text-ink-600">Pendekatan regeneratif di setiap kajian destinasi: memulihkan alam, budaya, dan ekonomi desa.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 rounded-2xl border border-ink-100 bg-cream-50 p-5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand-600 font-display text-lg font-bold text-white">E</span>
                        <div>
                            <h3 class="font-bold text-ink-950">Empowerment — Pemberdayaan</h3>
                            <p class="mt-1 text-sm leading-relaxed text-ink-600">Penguatan ekonomi desa dan koperasi: warga menjadi pelaku utama, bukan sekadar latar.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 rounded-2xl border border-ink-100 bg-cream-50 p-5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-ink-950 font-display text-lg font-bold text-white">E</span>
                        <div>
                            <h3 class="font-bold text-ink-950">Entrepreneurship — Kewirausahaan</h3>
                            <p class="mt-1 text-sm leading-relaxed text-ink-600">Komersialisasi nyata sebagai muara seluruh kerja GODEVI: produk desa bertemu pasar global.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div data-vue="Reveal" class="relative mt-10 overflow-hidden rounded-[2rem] bg-gradient-to-br from-brand-600 to-brand-800 px-6 py-14 text-center sm:px-16">
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-10 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
            <h2 class="font-display text-3xl font-bold text-white sm:text-4xl">Mari mengenal desa lebih dekat, bersama GODEVI.</h2>
            <p class="mx-auto mt-4 max-w-xl text-white/80">Jelajahi desa dampingan, pengalaman wisata, atau diskusikan kebutuhan riset dan pengembangan destinasi Anda.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url('village') }}" class="btn btn-white !px-8 !py-4">Jelajahi Desa</a>
                <a href="{{ url('contact') }}" class="btn border border-white/40 text-white hover:bg-white/10 !px-8 !py-4">Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>

@endsection
