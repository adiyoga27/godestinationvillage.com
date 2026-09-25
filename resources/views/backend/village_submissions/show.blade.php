@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Verifikasi Pengajuan Desa #{{ $submission->id }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item"><a href="{{ route('village-submissions.index') }}">Village Submissions</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">{{ $submission->village_name }}</h4>
            <table class="table">
                <tr><th width="220">Kontak</th><td>{{ $submission->contact_name }} — {{ $submission->phone }} — {{ $submission->email }}</td></tr>
                <tr><th>Alamat</th><td>{{ $submission->address }} ({{ $submission->regency }})</td></tr>
                <tr><th>Deskripsi</th><td>{{ $submission->description }}</td></tr>
                <tr><th>Potensi wisata</th><td>{{ $submission->tourism_potential }}</td></tr>
                <tr><th>Lampiran</th><td>@if ($submission->attachment)<a href="{{ asset('storage/village-submissions/'.$submission->attachment) }}" target="_blank">Lihat lampiran</a>@else - @endif</td></tr>
                <tr><th>Kode pelacakan</th><td><code>{{ $submission->uuid }}</code></td></tr>
                <tr><th>Catatan internal</th><td>{{ $submission->internal_note ?? '-' }}</td></tr>
            </table>
            <form action="{{ route('village-submissions.destroy', $submission->id) }}" method="post" onsubmit="return confirm('Hapus pengajuan ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Hapus</button>
            </form>
        </div></div>
    </div>
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">Inputan Tim</h4>
            <form action="{{ route('village-submissions.update', $submission->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Status verifikasi</label>
                    <select name="status" class="form-control" required>
                        @foreach (['pending', 'verified', 'rejected'] as $st)
                            <option value="{{ $st }}" {{ $submission->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>PIC Tim (Our Team)</label>
                    <select name="pic_team_id" class="form-control">
                        <option value="">— Belum ada —</option>
                        @foreach ($teams as $t)
                            <option value="{{ $t->id }}" {{ (int) $submission->pic_team_id === (int) $t->id ? 'selected' : '' }}>{{ $t->name }} — {{ $t->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Catatan internal</label>
                    <textarea name="internal_note" rows="4" class="form-control">{{ old('internal_note', $submission->internal_note) }}</textarea>
                </div>
                <button class="btn btn-gradient-danger btn-block">Simpan Verifikasi</button>
            </form>
        </div></div>
    </div>
</div>
@endsection
