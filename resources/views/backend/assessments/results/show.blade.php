@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Hasil Asesmen #{{ $result->id }} — {{ optional($result->track)->name }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item"><a href="{{ route('assessment-results.index') }}">Hasil Asesmen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">{{ $result->organization }} <small class="text-muted">— {{ $result->name }}</small></h4>
            <p>{{ collect([$result->subdistrict, $result->district, $result->regency, $result->province])->filter()->implode(', ') }}</p>
            <p>{{ collect([$result->phone, $result->email])->filter()->implode(' · ') }} · {{ $result->created_at }}</p>
            @if ($result->profile_description)
                <p class="mb-1"><strong>Deskripsi aset & potensi:</strong></p>
                <p style="white-space: pre-line">{{ $result->profile_description }}</p>
            @endif
            <p>Pembayaran: <label class="badge {{ $result->is_unlocked ? 'badge-gradient-success' : 'badge-gradient-warning' }}">{{ $result->paymentLabel() }}</label>
                @if ($result->latestOrder)
                    · {{ $result->latestOrder->code }} · Rp {{ number_format($result->latestOrder->amount, 0, ',', '.') }}{{ $result->latestOrder->payment_type ? ' · '.$result->latestOrder->payment_type : '' }}{{ $result->latestOrder->paid_at ? ' · '.$result->latestOrder->paid_at->format('d M Y H:i') : '' }}
                @endif
            </p>
            <p>Skor total: <strong>{{ number_format($result->total_score, 2) }}/100</strong> — <strong>{{ $result->band }}</strong></p>
            <a href="{{ url('asesmen/hasil/'.$result->uuid) }}" target="_blank" class="btn btn-sm btn-info mb-3">Lihat halaman hasil guest</a>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>Dimensi</th><th>Skor</th><th>Rata-rata</th></tr></thead>
                    <tbody>
                        @foreach (($result->dimension_scores ?? []) as $name => $dim)
                            <tr>
                                <td><strong>{{ $name }}</strong>@if (! empty($result->dimension_notes[$name]))<br><small class="text-muted" style="white-space: pre-line">Catatan: {{ $result->dimension_notes[$name] }}</small>@endif</td>
                                <td>{{ number_format($dim['score'] ?? 0, 1) }}</td>
                                <td>{{ $dim['average'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h5 class="mt-4">Jawaban (skala 1–5)</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead><tr><th>Dimensi</th><th>Pernyataan</th><th>Nilai</th></tr></thead>
                    <tbody>
                        @foreach (($result->dimension_scores ?? []) as $name => $dim)
                            @foreach (($dim['questions'] ?? []) as $item)
                                <tr><td>{{ $name }}</td><td>{{ $item['question'] }}</td><td><strong>{{ $item['score'] }}</strong></td></tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <form action="{{ route('assessment-results.destroy', $result->id) }}" method="post" onsubmit="return confirm('Hapus hasil ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Hapus</button>
            </form>
        </div></div>
    </div>
    <div class="col-lg-4 grid-margin stretch-card">
        <div class="card"><div class="card-body">
            <h4 class="card-title">Inputan Tim</h4>
            <form action="{{ route('assessment-results.update', $result->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Status tindak lanjut</label>
                    <select name="status" class="form-control" required>
                        @foreach (['baru', 'dihubungi', 'selesai'] as $st)
                            <option value="{{ $st }}" {{ $result->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>PIC Tim</label>
                    <select name="pic_team_id" class="form-control">
                        <option value="">— Belum ada —</option>
                        @foreach ($teams as $t)
                            <option value="{{ $t->id }}" {{ (int) $result->pic_team_id === (int) $t->id ? 'selected' : '' }}>{{ $t->name }} — {{ $t->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Catatan internal</label>
                    <textarea name="internal_note" rows="4" class="form-control">{{ old('internal_note', $result->internal_note) }}</textarea>
                </div>
                <button class="btn btn-gradient-danger btn-block">Simpan</button>
            </form>
        </div></div>
    </div>
</div>
@endsection
