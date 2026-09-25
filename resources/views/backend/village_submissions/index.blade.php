@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Pengajuan Desa Wisata</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item active" aria-current="page">Village Submissions</li>
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
                    <select name="status" class="form-control mr-2">
                        <option value="">Semua status</option>
                        @foreach (['pending', 'verified', 'rejected'] as $st)
                            <option value="{{ $st }}" {{ ($filters['status'] ?? '') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control mr-2" placeholder="Cari desa / kontak / email">
                    <button class="btn btn-primary">Filter</button>
                </form>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>ID</th><th>Desa</th><th>Kontak</th><th>Status</th><th>PIC</th><th>Tanggal</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($submissions as $s)
                                <tr>
                                    <td>{{ $s->id }}</td>
                                    <td><strong>{{ $s->village_name }}</strong><br><small>{{ $s->regency }}</small></td>
                                    <td>{{ $s->contact_name }}<br><small>{{ $s->email }}<br>{{ $s->phone }}</small></td>
                                    <td><label class="badge {{ $s->status === 'verified' ? 'badge-gradient-success' : ($s->status === 'rejected' ? 'badge-gradient-danger' : 'badge-gradient-warning') }}">{{ ucfirst($s->status) }}</label></td>
                                    <td>{{ optional($s->pic)->name ?? '-' }}</td>
                                    <td>{{ $s->created_at }}</td>
                                    <td><a href="{{ route('village-submissions.show', $s->id) }}" class="btn btn-sm btn-primary">Detail</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">Belum ada pengajuan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $submissions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
