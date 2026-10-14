@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Voucher Diskon Asesmen</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Asesmen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Voucher</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

                <div class="alert alert-info">
                    Guest memasukkan kode voucher di halaman pembayaran asesmen. Voucher <strong>Gratis</strong> langsung membuka hasil &amp; laporan AI tanpa pembayaran;
                    voucher persen/nominal memotong tagihan Midtrans. "Terpakai" dihitung dari invoice yang sudah lunas.
                </div>

                <a href="{{ route('assessment-vouchers.create') }}" class="btn btn-lg btn-gradient-danger"><i class="mdi mdi-plus-circle-outline"></i> Buat Voucher (satu / bulk)</a>
                <a href="{{ route('assessment-vouchers.export', array_filter(['batch' => $batch])) }}" class="btn btn-lg btn-outline-primary"><i class="mdi mdi-download"></i> Unduh CSV{{ $batch ? ' batch ini' : '' }}</a>
                <a href="{{ route('assessment-prices.index') }}" class="btn btn-lg btn-outline-secondary"><i class="mdi mdi-cash"></i> Harga Asesmen</a>

                <form method="get" action="{{ route('assessment-vouchers.index') }}" class="form-inline mt-4" style="gap:.5rem">
                    <select name="batch" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua voucher</option>
                        @foreach ($batches as $b)
                            <option value="{{ $b->batch }}" {{ $batch === $b->batch ? 'selected' : '' }}>Batch {{ $b->batch }} ({{ $b->total }} kode, {{ (int) $b->active }} aktif)</option>
                        @endforeach
                    </select>
                    <input type="text" name="q" value="{{ $search }}" class="form-control text-uppercase" placeholder="Cari kode…">
                    <button class="btn btn-outline-secondary">Cari</button>
                    @if ($batch || $search !== '')<a href="{{ route('assessment-vouchers.index') }}" class="btn btn-link">Reset</a>@endif
                </form>

                @if ($batch)
                    @php $current = $batches->firstWhere('batch', $batch); @endphp
                    <div class="alert alert-light border mt-3 mb-0 d-flex flex-wrap align-items-center justify-content-between" style="gap:.5rem">
                        <span>Batch <strong class="text-monospace">{{ $batch }}</strong>: {{ $current->total ?? 0 }} kode, {{ (int) ($current->active ?? 0) }} aktif.</span>
                        <span>
                            @foreach ([1 => 'Aktifkan semua', 0 => 'Nonaktifkan semua'] as $active => $label)
                                <form method="post" action="{{ route('assessment-vouchers.batch-toggle') }}" class="d-inline" onsubmit="return confirm('{{ $label }} kode di batch ini?')">
                                    @csrf
                                    <input type="hidden" name="batch" value="{{ $batch }}">
                                    <input type="hidden" name="active" value="{{ $active }}">
                                    <button class="btn btn-sm {{ $active ? 'btn-gradient-success' : 'btn-outline-secondary' }}">{{ $label }}</button>
                                </form>
                            @endforeach
                        </span>
                    </div>
                @endif

                <div class="table-responsive mt-4">
                    <table class="table table-hover">
                        <thead><tr><th>Kode</th><th>Diskon</th><th>Jalur</th><th>Terpakai</th><th>Masa berlaku</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($vouchers as $v)
                                @php $status = $v->statusLabel(); @endphp
                                <tr>
                                    <td><strong class="text-monospace">{{ $v->code }}</strong>@if ($v->description)<br><small class="text-muted">{{ $v->description }}</small>@endif @if ($v->batch && ! $batch)<br><a href="{{ route('assessment-vouchers.index', ['batch' => $v->batch]) }}" class="badge badge-light">batch {{ $v->batch }}</a>@endif</td>
                                    <td>{{ $v->label() }}</td>
                                    <td style="white-space:normal;max-width:220px">
                                        @if (empty($v->track_ids))
                                            <small>Semua jalur</small>
                                        @else
                                            <small>{{ collect($v->track_ids)->map(fn ($id) => $tracks[$id] ?? '#'.$id)->implode(', ') }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $v->usedCount() }}{{ $v->max_uses ? ' / '.$v->max_uses : '' }}</td>
                                    <td><small>{{ $v->starts_at?->format('d M Y H:i') ?? 'Sejak dibuat' }}<br>s/d {{ $v->expires_at?->format('d M Y H:i') ?? 'tanpa batas' }}</small></td>
                                    <td><span class="badge {{ $status === 'Aktif' ? 'badge-success' : ($status === 'Terjadwal' ? 'badge-info' : 'badge-secondary') }}">{{ $status }}</span></td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('assessment-vouchers.edit', $v->id) }}" class="btn btn-sm btn-gradient-info"><i class="mdi mdi-pencil"></i> Edit</a>
                                        <form method="POST" action="{{ route('assessment-vouchers.toggle', $v->id) }}" style="display:inline">
                                            @csrf
                                            <button class="btn btn-sm {{ $v->is_active ? 'btn-outline-secondary' : 'btn-gradient-success' }}" title="{{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"><i class="mdi {{ $v->is_active ? 'mdi-pause' : 'mdi-play' }}"></i></button>
                                        </form>
                                        <form method="POST" action="{{ route('assessment-vouchers.destroy', $v->id) }}" style="display:inline" onsubmit="return confirm('Hapus voucher {{ $v->code }}? Voucher yang sudah pernah dipakai hanya dinonaktifkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-gradient-danger" title="Hapus"><i class="mdi mdi-delete"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Belum ada voucher.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
