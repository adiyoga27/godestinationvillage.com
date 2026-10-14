@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Harga Asesmen</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Asesmen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Harga</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-9 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

                <div class="alert alert-info">
                    Harga = biaya membuka skor &amp; laporan strategi per asesmen. Isi <strong>0</strong> untuk jalur gratis (hasil langsung terbuka tanpa pembayaran).
                    Harga baru berlaku untuk invoice berikutnya; invoice yang sudah terbit tetap memakai harga lama.
                    Untuk potongan harga atau gratis bagi orang tertentu, pakai <a href="{{ route('assessment-vouchers.index') }}">Voucher Diskon</a>.
                </div>

                <form action="{{ route('assessment-prices.update') }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead><tr><th>Jalur</th><th style="width:240px">Harga (Rp)</th><th>Menunggu bayar</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach ($tracks as $t)
                                    <tr>
                                        <td><strong>{{ $t->name }}</strong>@if ($t->name_en)<br><small class="text-muted">{{ $t->name_en }}</small>@endif</td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                                <input type="number" name="prices[{{ $t->id }}]" value="{{ old('prices.'.$t->id, $t->price) }}" min="0" step="1000" class="form-control" required data-gd-money="price-{{ $t->id }}">
                                            </div>
                                            <small class="text-muted" data-gd-money-preview="price-{{ $t->id }}"></small>
                                        </td>
                                        <td>{{ $t->pending_count }}</td>
                                        <td>{!! $t->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Nonaktif</span>' !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-gradient-danger mt-3"><i class="mdi mdi-content-save"></i> Simpan Harga</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
