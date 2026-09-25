@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Dashboard Tim — Inbox 4 Alur</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard Tim</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3 grid-margin stretch-card"><div class="card"><div class="card-body"><p class="card-title">Desa Pending</p><h3>{{ $stats['submission_pending'] }}</h3><p class="text-muted">dari {{ $stats['submission_total'] }} pengajuan</p></div></div></div>
    <div class="col-md-3 grid-margin stretch-card"><div class="card"><div class="card-body"><p class="card-title">Paket Pending / Sukses</p><h3>{{ $stats['package_pending'] }} / {{ $stats['package_success'] }}</h3><p class="text-muted">Midtrans Snap</p></div></div></div>
    <div class="col-md-3 grid-margin stretch-card"><div class="card"><div class="card-body"><p class="card-title">Event Pending / Sukses</p><h3>{{ $stats['event_pending'] }} / {{ $stats['event_success'] }}</h3><p class="text-muted">termasuk tiket gratis</p></div></div></div>
    <div class="col-md-3 grid-margin stretch-card"><div class="card"><div class="card-body"><p class="card-title">Homestay Pending / Sukses</p><h3>{{ $stats['homestay_pending'] }} / {{ $stats['homestay_success'] }}</h3><p class="text-muted">{{ $stats['unassigned'] }} belum ada PIC</p></div></div></div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">Asesmen Masuk — {{ $stats['assessment_new'] }} baru <a href="{{ route('assessment-results.index') }}" class="btn btn-sm btn-gradient-danger ml-2">Kelola</a> <a href="{{ route('assessments.index') }}" class="btn btn-sm btn-secondary">Jalur & Soal</a></h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>Tanggal</th><th>Jalur</th><th>Pengisi</th><th>Skor</th><th>Band</th><th>Status</th><th>PIC</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($assessmentResults as $r)
                            <tr>
                                <td>{{ $r->created_at }}</td>
                                <td>{{ optional($r->track)->name }}</td>
                                <td><strong>{{ $r->name }}</strong><br><small>{{ $r->organization }} — {{ $r->email }}</small></td>
                                <td><strong>{{ number_format($r->total_score, 0) }}</strong></td>
                                <td>{{ $r->band }}</td>
                                <td><label class="badge {{ $r->status === 'selesai' ? 'badge-gradient-success' : ($r->status === 'dihubungi' ? 'badge-gradient-info' : 'badge-gradient-warning') }}">{{ ucfirst($r->status) }}</label></td>
                                <td>{{ optional($r->pic)->name ?? '-' }}</td>
                                <td><a href="{{ route('assessment-results.show', $r->id) }}" class="btn btn-sm btn-primary">Tindak lanjut</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">Belum ada hasil asesmen.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Alur 1 — Pengajuan Desa Terbaru <a href="{{ route('village-submissions.index') }}" class="btn btn-sm btn-gradient-danger ml-2">Kelola</a></h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Desa</th><th>Kontak</th><th>Status</th><th>PIC Tim</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @forelse ($submissions as $s)
                                <tr>
                                    <td><strong>{{ $s->village_name }}</strong><br><small>{{ $s->regency }} — {{ $s->created_at }}</small></td>
                                    <td>{{ $s->contact_name }}<br><small>{{ $s->email }} / {{ $s->phone }}</small></td>
                                    <td><label class="badge {{ $s->status === 'verified' ? 'badge-gradient-success' : ($s->status === 'rejected' ? 'badge-gradient-danger' : 'badge-gradient-warning') }}">{{ ucfirst($s->status) }}</label></td>
                                    <td>{{ optional($s->pic)->name ?? '-' }}</td>
                                    <td><a href="{{ route('village-submissions.show', $s->id) }}" class="btn btn-sm btn-primary">Verifikasi</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">Belum ada pengajuan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Inputan Tim — Assign PIC + Catatan + Status (berlaku untuk 4 alur)</h4>
                <form action="{{ route('team-dashboard.assign') }}" method="post" class="form-inline">
                    @csrf
                    <select name="type" class="form-control mr-2 mb-2" required>
                        <option value="village_submission">Alur 1: Desa</option>
                        <option value="package">Alur 2: Paket</option>
                        <option value="event">Alur 3: Event</option>
                        <option value="homestay">Alur 4: Homestay</option>
                        <option value="assessment">Asesmen</option>
                    </select>
                    <input type="number" name="id" class="form-control mr-2 mb-2" placeholder="ID data" required>
                    <select name="pic_team_id" class="form-control mr-2 mb-2">
                        <option value="">— PIC Tim —</option>
                        @foreach ($teams as $t)
                            <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->title }})</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-control mr-2 mb-2">
                        <option value="">— Status —</option>
                        <option value="pending">pending</option>
                        <option value="verified">verified (desa)</option>
                        <option value="success">success (booking)</option>
                        <option value="rejected">rejected (desa)</option>
                        <option value="cancel">cancel (booking)</option>
                        <option value="baru">baru (asesmen)</option>
                        <option value="dihubungi">dihubungi (asesmen)</option>
                        <option value="selesai">selesai (asesmen)</option>
                    </select>
                    <input type="text" name="internal_note" class="form-control mr-2 mb-2" placeholder="Catatan internal" style="min-width:220px">
                    <button class="btn btn-gradient-danger mb-2">Simpan</button>
                </form>
                <p class="text-muted">Contoh: pilih <em>Alur 2: Paket</em> + ID order paket + PIC + catatan “sudah hubungi tamu via WA”, lalu Simpan.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">Alur 2 — Order Paket Terbaru</h4>
            <div class="table-responsive"><table class="table table-hover">
                <thead><tr><th>Kode</th><th>Tamu</th><th>Total</th><th>Status</th><th>PIC</th></tr></thead>
                <tbody>
                    @forelse ($packageOrders as $o)
                        <tr><td>{{ $o->code }}</td><td>{{ $o->customer_name }}<br><small>{{ $o->customer_email }}</small></td><td>Rp {{ number_format($o->total_payment, 0, ',', '.') }}</td><td>{{ $o->payment_status }}</td><td>{{ optional($o->pic)->name ?? '-' }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Belum ada order.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
            <a href="{{ url('administrator/orders') }}" class="btn btn-sm btn-secondary mt-2">Buka manajemen order paket</a>
        </div></div>
    </div>
    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">Alur 3 — Order Event Terbaru</h4>
            <div class="table-responsive"><table class="table table-hover">
                <thead><tr><th>Kode</th><th>Tamu</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($eventOrders as $o)
                        <tr><td>{{ $o->code }}</td><td>{{ $o->customer_name }}<br><small>{{ $o->customer_email }}</small></td><td>Rp {{ number_format($o->total_payment, 0, ',', '.') }}</td><td>{{ $o->payment_status }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Belum ada order.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
            <a href="{{ url('administrator/order-event') }}" class="btn btn-sm btn-secondary mt-2">Buka manajemen order event</a>
        </div></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">Alur 4 — Order Homestay Terbaru</h4>
            <div class="table-responsive"><table class="table table-hover">
                <thead><tr><th>Kode</th><th>Tamu</th><th>Homestay</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($homestayOrders as $o)
                        <tr><td>{{ $o->code }}</td><td>{{ $o->customer_name }}<br><small>{{ $o->customer_email }}</small></td><td>{{ $o->homestay_name }}</td><td>Rp {{ number_format($o->total_payment, 0, ',', '.') }}</td><td>{{ $o->payment_status }}</td></tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Belum ada order.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
            <a href="{{ url('administrator/order-homestay') }}" class="btn btn-sm btn-secondary mt-2">Buka manajemen order homestay</a>
        </div></div>
    </div>
</div>
@endsection
