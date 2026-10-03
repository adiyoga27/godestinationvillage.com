@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <div>
            <a href="{{ route('assessment-results.index') }}" class="gd-back"><i class="mdi mdi-arrow-left"></i> Hasil Asesmen</a>
            <h3 class="page-title mb-0">{{ $result->organization ?: $result->name }}</h3>
        </div>
        <div class="d-flex flex-wrap" style="gap:.5rem">
            <a href="{{ route('assessment.result', $result->uuid) }}" target="_blank" rel="noopener" class="gd-btn gd-btn--ghost"><i class="mdi mdi-open-in-new"></i> Halaman guest</a>
            @if ($result->is_unlocked)
                <form action="{{ route('assessment-results.regenerate', $result->id) }}" method="post" onsubmit="return confirm('Buat ulang laporan AI? Laporan lama akan diganti.')">
                    @csrf
                    <button class="gd-btn gd-btn--ghost"><i class="mdi mdi-refresh"></i> Buat ulang laporan AI</button>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('content')
@php
    $track = $result->track;
    $rep = $result->ai_report ?? [];
    $dims = $result->dimension_scores ?? [];
    $notes = $result->dimension_notes ?? [];
    $computed = $result->computed_result ?? [];
    $meta = $result->ai_meta ?? [];
    $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
    $score = (float) $result->total_score;
    $tone = fn ($s) => $s >= 60 ? 'green' : ($s >= 40 ? 'amber' : 'red');
    $totalWeight = max(1, collect($dims)->sum('weight'));
    // Item laporan AI bisa string atau objek; objek ditampilkan sebagai judul + keterangan.
    $item = function ($v) {
        if (! is_array($v)) {
            return ['title' => null, 'body' => (string) $v];
        }
        $title = $v['nama'] ?? $v['produk'] ?? $v['model'] ?? $v['segmen'] ?? $v['judul'] ?? $v['name'] ?? null;
        $rest = collect($v)->except(['nama', 'produk', 'model', 'segmen', 'judul', 'name'])
            ->map(fn ($x) => is_array($x) ? implode(', ', $x) : $x)->filter()->implode(' — ');

        return ['title' => $title, 'body' => $rest];
    };
    $extraSections = [
        'segmen_pasar' => ['Segmen pasar', 'account-multiple'],
        'strategi_pemasaran' => ['Strategi pemasaran', 'bullhorn'],
        'produk_unggulan_potensial' => ['Produk unggulan potensial', 'star'],
        'model_bisnis_disarankan' => ['Model bisnis disarankan', 'briefcase'],
        'catatan_kategori' => ['Catatan per subindeks', 'format-list-bulleted'],
        'prioritas_kebijakan' => ['Prioritas kebijakan', 'gavel'],
        'rekomendasi_regeneratif' => ['Rekomendasi regeneratif', 'leaf'],
        'sertifikasi_relevan' => ['Sertifikasi relevan', 'certificate'],
        'layanan_godevi_disarankan' => ['Layanan GODEVI yang disarankan', 'hand-pointing-right'],
    ];
    $emailTypes = ['invoice' => 'Invoice', 'paid' => 'Pembayaran berhasil', 'report' => 'Hasil & strategi'];
@endphp

{{-- ============ RINGKASAN ============ --}}
<section class="gd-hero-card">
    <div class="gd-ring gd-ring--{{ $tone($score) }}" style="--p: {{ max(0, min(100, $score)) }}">
        <span><strong>{{ number_format($score, 0) }}</strong><small>/100</small></span>
    </div>
    <div class="gd-hero-card__body">
        <p class="gd-eyebrow">{{ $track->name ?? '-' }} · #{{ $result->id }}</p>
        <h2 class="gd-hero-card__title">{{ $result->band }}</h2>
        <div class="gd-hero-card__pills">
            <span class="gd-pill {{ $result->is_unlocked ? 'gd-pill--green' : 'gd-pill--amber' }}">{{ $result->paymentLabel() }}</span>
            <span class="gd-pill gd-pill--ink">{{ $result->source === 'admin' ? 'Input admin'.($result->creator ? ' · '.$result->creator->name : '') : 'Diisi guest' }}</span>
            <span class="gd-pill gd-pill--ink">{{ $result->created_at?->format('d M Y H:i') }}</span>
            @if ($result->report_status)
                <span class="gd-pill {{ ['done' => 'gd-pill--green', 'failed' => 'gd-pill--red', 'generating' => 'gd-pill--blue'][$result->report_status] ?? 'gd-pill--amber' }}">Laporan AI: {{ ['done' => 'selesai', 'failed' => 'gagal', 'generating' => 'diproses', 'pending' => 'menunggu'][$result->report_status] ?? $result->report_status }}</span>
            @endif
        </div>
    </div>
</section>

<div class="gd-form mt-4">
    <div class="gd-form__main">

        {{-- ============ LAPORAN AI ============ --}}
        @include('backend.partials.form.card-open', ['icon' => 'robot', 'title' => 'Laporan Analisa & Strategi', 'desc' => 'Dibuat AI dari skor, profil, dan catatan responden.'])
            @if (! empty($rep))
                @if (! empty($rep['ringkasan']))
                    <blockquote class="gd-quote">{{ $rep['ringkasan'] }}</blockquote>
                @endif

                <div class="gd-grid-2 mt-3">
                    <div class="gd-panel gd-panel--green">
                        <h4><i class="mdi mdi-check-circle"></i> Kekuatan utama</h4>
                        <ul>@foreach ((array) ($rep['kekuatan'] ?? []) as $v)<li>{{ $item($v)['body'] }}</li>@endforeach</ul>
                    </div>
                    <div class="gd-panel gd-panel--red">
                        <h4><i class="mdi mdi-alert"></i> Tantangan utama</h4>
                        <ul>@foreach ((array) ($rep['tantangan'] ?? []) as $v)<li>{{ $item($v)['body'] }}</li>@endforeach</ul>
                    </div>
                </div>

                @if (! empty($rep['langkah_prioritas']))
                    <h4 class="gd-section-title">Langkah prioritas</h4>
                    <ol class="gd-steps">
                        @foreach ((array) $rep['langkah_prioritas'] as $v)
                            @php $it = $item($v); @endphp
                            <li><span class="gd-steps__n">{{ $loop->iteration }}</span><div>@if ($it['title'])<strong>{{ $it['title'] }}</strong><br>@endif{{ $it['body'] }}</div></li>
                        @endforeach
                    </ol>
                @endif

                @if (! empty($rep['posisi_spektrum']))
                    <div class="gd-callout gd-callout--green"><strong>Posisi spektrum:</strong> {{ is_array($rep['posisi_spektrum']) ? $item($rep['posisi_spektrum'])['body'] : $rep['posisi_spektrum'] }}</div>
                @endif

                @if (! empty($rep['opsi_branding']))
                    <h4 class="gd-section-title">Opsi branding</h4>
                    <div class="gd-brand-grid">
                        @foreach ((array) $rep['opsi_branding'] as $b)
                            <div class="gd-brand-card">
                                <p class="gd-brand-card__name">{{ is_array($b) ? ($b['nama'] ?? '') : $b }}</p>
                                @if (is_array($b))
                                    @if (! empty($b['tagline']))<p class="gd-brand-card__tag">“{{ $b['tagline'] }}”</p>@endif
                                    <p class="gd-brand-card__why">{{ $b['alasan'] ?? '' }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @foreach ($extraSections as $key => [$title, $icon])
                    @if (! empty($rep[$key]))
                        <h4 class="gd-section-title"><i class="mdi mdi-{{ $icon }}"></i> {{ $title }}</h4>
                        <ul class="gd-list">
                            @foreach ((array) $rep[$key] as $v)
                                @php $it = $item($v); @endphp
                                <li>@if ($it['title'])<strong>{{ $it['title'] }}</strong>@if ($it['body']) — @endif @endif{{ $it['body'] }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach

                @if (! empty($rep['catatan_ttdi']))
                    <div class="gd-callout gd-callout--amber">{{ is_array($rep['catatan_ttdi']) ? $item($rep['catatan_ttdi'])['body'] : $rep['catatan_ttdi'] }}</div>
                @endif
            @elseif (! $result->is_unlocked)
                <div class="gd-empty"><i class="mdi mdi-lock"></i><p><strong>Laporan terkunci.</strong> Laporan AI dibuat otomatis setelah pembayaran lunas atau di-approve manual.</p></div>
            @elseif ($result->report_status === 'failed')
                <div class="gd-empty gd-empty--red"><i class="mdi mdi-alert-circle"></i><p><strong>Pembuatan laporan gagal.</strong> {{ $result->report_error }}<br>Periksa <code>DEEPSEEK_API_KEY</code>, lalu klik <em>Buat ulang laporan AI</em>.</p></div>
            @else
                <div class="gd-empty"><i class="mdi mdi-timer-sand"></i><p><strong>Laporan sedang disusun.</strong> Muat ulang halaman ini beberapa saat lagi.</p></div>
            @endif
        @include('backend.partials.form.card-close')

        {{-- ============ SKOR PER DIMENSI ============ --}}
        @include('backend.partials.form.card-open', ['icon' => 'chart-bar', 'title' => 'Skor per Dimensi', 'desc' => 'Rata-rata 1–5 dinormalisasi ke 0–100, beserta catatan responden.'])
            @if (! empty($computed['subindexes']))
                <div class="gd-subindex">
                    @foreach ($computed['subindexes'] as $name => $val)
                        <div class="gd-subindex__item"><span>{{ $name }}</span><strong class="gd-text--{{ $tone($val) }}">{{ number_format($val, 0) }}</strong></div>
                    @endforeach
                </div>
            @endif
            <div class="gd-dims">
                @foreach ($dims as $name => $dim)
                    @php $s = (float) ($dim['score'] ?? 0); @endphp
                    <div class="gd-dim">
                        <div class="gd-dim__head">
                            <span class="gd-dim__name">{{ $name }}</span>
                            <span class="gd-dim__score gd-text--{{ $tone($s) }}">{{ number_format($s, 0) }}</span>
                        </div>
                        <div class="gd-bar"><span class="gd-bar__fill gd-bar__fill--{{ $tone($s) }}" style="width: {{ max(2, $s) }}%"></span></div>
                        <div class="gd-dim__meta">Rata-rata {{ $dim['average'] ?? '-' }}/5 · Bobot {{ round(($dim['weight'] ?? 0) / $totalWeight * 100) }}%</div>
                        @if (! empty($notes[$name]))
                            <div class="gd-dim__note"><i class="mdi mdi-comment-text-outline"></i> {{ $notes[$name] }}</div>
                        @endif
                    </div>
                @endforeach
            </div>

            <details class="gd-details mt-3">
                <summary>Lihat jawaban per pernyataan</summary>
                <div class="table-responsive mt-2">
                    <table class="table gd-table">
                        <thead><tr><th>Dimensi</th><th>Pernyataan</th><th class="text-right">Nilai</th></tr></thead>
                        <tbody>
                            @foreach ($dims as $name => $dim)
                                @foreach (($dim['questions'] ?? []) as $q)
                                    <tr><td class="gd-table__meta">{{ $name }}</td><td>{{ $q['question'] }}</td><td class="text-right"><strong>{{ $q['score'] }}</strong></td></tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        @include('backend.partials.form.card-close')

        {{-- ============ ARSIP AI ============ --}}
        @if ($meta || $result->ai_prompt)
            @include('backend.partials.form.card-open', ['icon' => 'archive', 'title' => 'Arsip AI', 'desc' => 'Model, pemakaian token, dan prompt yang dikirim.'])
                <div class="gd-kv">
                    <div><span>Driver / model</span><strong>{{ ($meta['driver'] ?? '-').' / '.($meta['model'] ?? '-') }}</strong></div>
                    <div><span>Dibuat</span><strong>{{ ! empty($meta['generated_at']) ? \Illuminate\Support\Carbon::parse($meta['generated_at'])->format('d M Y H:i') : '-' }}</strong></div>
                    <div><span>Total token</span><strong>{{ number_format($meta['usage']['total_tokens'] ?? 0) }}</strong></div>
                    <div><span>Percobaan</span><strong>{{ count($meta['attempts'] ?? []) }}</strong></div>
                </div>
                @foreach (($meta['attempts'] ?? []) as $a)
                    @if (empty($a['ok']))
                        <div class="gd-callout gd-callout--red mt-2">Percobaan {{ $loop->iteration }} gagal: {{ $a['error'] ?? '-' }}</div>
                    @endif
                @endforeach
                @if ($result->ai_prompt)
                    <details class="gd-details mt-3">
                        <summary>Lihat prompt</summary>
                        <pre class="gd-pre">{{ $result->ai_prompt }}</pre>
                    </details>
                @endif
            @include('backend.partials.form.card-close')
        @endif
    </div>

    <aside class="gd-form__side">
        {{-- ============ PEMBAYARAN ============ --}}
        @include('backend.partials.form.card-open', ['icon' => 'receipt', 'title' => 'Invoice & Pembayaran', 'desc' => 'Harga jalur: '.$rupiah($track->price ?? 0)])
            @forelse ($result->orders->sortByDesc('id') as $o)
                <div class="gd-invoice">
                    <div class="gd-invoice__head">
                        <strong>{{ $o->code }}</strong>
                        <span class="gd-pill {{ ['paid' => 'gd-pill--green', 'pending' => 'gd-pill--amber'][$o->status] ?? 'gd-pill--red' }}">{{ ['paid' => 'Lunas', 'pending' => 'Menunggu', 'expired' => 'Kedaluwarsa', 'failed' => 'Gagal', 'refunded' => 'Refund'][$o->status] ?? $o->status }}</span>
                    </div>
                    <div class="gd-invoice__amount">{{ $rupiah($o->amount) }}</div>
                    <div class="gd-table__meta">{{ strtoupper($o->gateway) }}{{ $o->payment_type ? ' · '.str_replace('_', ' ', $o->payment_type) : '' }}</div>
                    <div class="gd-table__meta">Dibuat {{ $o->created_at?->format('d M Y H:i') }}{{ $o->paid_at ? ' · Lunas '.$o->paid_at->format('d M Y H:i') : '' }}</div>
                </div>
            @empty
                <p class="gd-hint mb-0">{{ $result->source === 'admin' ? 'Input admin — tanpa invoice.' : 'Guest belum checkout, belum ada invoice.' }}</p>
            @endforelse

            @if ($result->approved_by)
                <div class="gd-callout gd-callout--green mt-3">
                    Di-approve manual oleh <strong>{{ $result->approver->name ?? '#'.$result->approved_by }}</strong> · {{ $result->approved_at?->format('d M Y H:i') }}
                    @if ($result->approval_note)<br><em>{{ $result->approval_note }}</em>@endif
                </div>
            @endif

            @if (! $result->is_unlocked)
                <form action="{{ route('assessment-results.approve', $result->id) }}" method="post" class="mt-3" onsubmit="return confirm('Approve pembayaran secara manual? Hasil terbuka dan laporan AI dibuat.')">
                    @csrf
                    <label class="gd-label">Catatan approve <span class="text-muted font-weight-normal">(opsional)</span></label>
                    <input type="text" name="approval_note" maxlength="500" class="form-control gd-input" placeholder="mis. Transfer BCA 3 Okt, bukti di WA">
                    <button class="gd-btn gd-btn--success w-100 mt-2"><i class="mdi mdi-check-circle"></i> Approve Pembayaran Manual</button>
                </form>
            @endif
        @include('backend.partials.form.card-close')

        {{-- ============ PROFIL ============ --}}
        @include('backend.partials.form.card-open', ['icon' => 'account-card-details', 'title' => 'Profil Responden'])
            <div class="gd-kv gd-kv--stack">
                <div><span>Nama usaha / desa / kawasan</span><strong>{{ $result->organization ?: '-' }}</strong></div>
                @if ($result->institution)<div><span>Instansi / OPD pengusul</span><strong>{{ $result->institution }}</strong></div>@endif
                @if ($result->business_type)<div><span>Jenis</span><strong>{{ $result->business_type }}</strong></div>@endif
                @if ($result->business_sector)<div><span>Sektor</span><strong>{{ $result->business_sector }}</strong></div>@endif
                @if ($result->member_count)<div><span>Anggota / pelaku usaha</span><strong>{{ $result->member_count }}</strong></div>@endif
                <div><span>Lokasi</span><strong>{{ collect([$result->subdistrict ? 'Kel. '.$result->subdistrict : null, $result->district ? 'Kec. '.$result->district : null, $result->regency, $result->province ? 'Prov. '.$result->province : null])->filter()->implode(', ') }} {{ $result->postal_code }}</strong></div>
                <div><span>Kontak</span><strong>{{ $result->name }}</strong></div>
                <div><span>WhatsApp</span><strong>@if ($result->phone)<a href="https://wa.me/62{{ ltrim($result->phone, '0') }}" target="_blank" rel="noopener">{{ $result->phone }}</a>@else - @endif</strong></div>
                <div><span>Email</span><strong>@if ($result->email)<a href="mailto:{{ $result->email }}">{{ $result->email }}</a>@else - @endif</strong></div>
            </div>
            @if ($result->profile_description)
                <p class="gd-label mt-3 mb-1">Deskripsi</p>
                <p class="gd-prose">{{ $result->profile_description }}</p>
            @endif
        @include('backend.partials.form.card-close')

        {{-- ============ EMAIL ============ --}}
        @include('backend.partials.form.card-open', ['icon' => 'email', 'title' => 'Email ke Responden', 'desc' => $result->email ?: 'Belum ada email'])
            @forelse (array_reverse($result->email_log ?? []) as $log)
                <div class="gd-log">
                    <i class="mdi {{ ! empty($log['ok']) ? 'mdi-check-circle gd-text--green' : 'mdi-close-circle gd-text--red' }}"></i>
                    <div>
                        <strong>{{ $emailTypes[$log['type']] ?? $log['type'] }}</strong>{{ ! empty($log['order']) ? ' · '.$log['order'] : '' }}
                        <div class="gd-table__meta">{{ \Illuminate\Support\Carbon::parse($log['at'])->format('d M Y H:i') }} · {{ $log['to'] }}</div>
                        @if (! empty($log['error']))<div class="gd-table__meta gd-text--red">{{ $log['error'] }}</div>@endif
                    </div>
                </div>
            @empty
                <p class="gd-hint">Belum ada email terkirim.</p>
            @endforelse
            @php $sendable = $result->sendableEmails(); @endphp
            @if (! $result->email)
                <p class="gd-hint mb-0">Responden belum mengisi email.</p>
            @elseif (empty($sendable))
                <p class="gd-hint mb-0">{{ $result->is_unlocked ? 'Email hasil bisa dikirim setelah laporan AI selesai.' : 'Jalur ini gratis — tidak ada invoice.' }}</p>
            @else
                <form action="{{ route('assessment-results.email', $result->id) }}" method="post" class="gd-inline-form mt-2" data-confirm="Kirim email ke {{ $result->email }}?">
                    @csrf
                    <select name="type" class="form-control gd-input" aria-label="Jenis email">
                        @foreach ($sendable as $k => $label)<option value="{{ $k }}" @selected($loop->last)>{{ $label }}</option>@endforeach
                    </select>
                    <button class="gd-btn gd-btn--ghost"><i class="mdi mdi-send"></i> Kirim ulang</button>
                </form>
            @endif
        @include('backend.partials.form.card-close')

        {{-- ============ TINDAK LANJUT ============ --}}
        @include('backend.partials.form.card-open', ['icon' => 'account-check', 'title' => 'Tindak Lanjut Tim'])
            <form action="{{ route('assessment-results.update', $result->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="gd-field">
                    <label class="gd-label">Status</label>
                    <select name="status" class="form-control gd-input" required>
                        @foreach (['baru', 'dihubungi', 'selesai'] as $st)
                            <option value="{{ $st }}" @selected($result->status === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="gd-field">
                    <label class="gd-label">PIC Tim</label>
                    <select name="pic_team_id" class="form-control gd-input">
                        <option value="">— Belum ada —</option>
                        @foreach ($teams as $t)
                            <option value="{{ $t->id }}" @selected((int) $result->pic_team_id === (int) $t->id)>{{ $t->name }} — {{ $t->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="gd-field">
                    <label class="gd-label">Catatan internal</label>
                    <textarea name="internal_note" rows="4" class="form-control gd-input" style="height:auto">{{ old('internal_note', $result->internal_note) }}</textarea>
                </div>
                <button class="gd-btn gd-btn--primary w-100"><i class="mdi mdi-content-save"></i> Simpan</button>
            </form>
            <form action="{{ route('assessment-results.destroy', $result->id) }}" method="post" class="mt-2" onsubmit="return confirm('Hapus hasil ini beserta invoicenya?')">
                @csrf
                @method('DELETE')
                <button class="gd-btn gd-btn--danger-ghost w-100"><i class="mdi mdi-delete"></i> Hapus hasil</button>
            </form>
        @include('backend.partials.form.card-close')
    </aside>
</div>
@endsection
