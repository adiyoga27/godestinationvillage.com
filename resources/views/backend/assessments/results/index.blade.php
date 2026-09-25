@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Hasil Asesmen Masuk</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item active" aria-current="page">Hasil Asesmen</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form method="get" class="form-inline mb-3">
                    <select name="track_id" class="form-control mr-2">
                        <option value="">Semua jalur</option>
                        @foreach ($tracks as $t)
                            <option value="{{ $t->id }}" {{ ($filters['track_id'] ?? '') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" class="form-control mr-2">
                        <option value="">Semua status</option>
                        @foreach (['baru', 'dihubungi', 'selesai'] as $st)
                            <option value="{{ $st }}" {{ ($filters['status'] ?? '') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control mr-2" placeholder="Cari nama / email / organisasi">
                    <button class="btn btn-primary">Filter</button>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Tanggal</th><th>Jalur</th><th>Pengisi</th><th>Skor</th><th>Band</th><th>Status</th><th>PIC</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($results as $r)
                                <tr>
                                    <td>{{ $r->created_at }}</td>
                                    <td>{{ optional($r->track)->name }}</td>
                                    <td><strong>{{ $r->name }}</strong><br><small>{{ $r->organization }}<br>{{ $r->email }} / {{ $r->phone }}</small></td>
                                    <td><strong>{{ number_format($r->total_score, 0) }}</strong></td>
                                    <td>{{ $r->band }}</td>
                                    <td><label class="badge {{ $r->status === 'selesai' ? 'badge-gradient-success' : ($r->status === 'dihubungi' ? 'badge-gradient-info' : 'badge-gradient-warning') }}">{{ ucfirst($r->status) }}</label></td>
                                    <td>{{ optional($r->pic)->name ?? '-' }}</td>
                                    <td><a href="{{ route('assessment-results.show', $r->id) }}" class="btn btn-sm btn-primary">Detail</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center">Belum ada hasil masuk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $results->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
