@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Bank Soal: {{ $track->name }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Asesmen</a></li>
                <li class="breadcrumb-item active" aria-current="page">Soal</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('assessments.questions.create', $track->id) }}" class="btn btn-lg btn-gradient-danger mr-2">
                    <i class="mdi mdi-plus-circle-outline"></i> Tambah Soal
                </a>
                <br /><br />
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>#</th><th>Dimensi</th><th>Pernyataan</th><th>Bobot</th><th>Aktif</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($questions as $q)
                                <tr>
                                    <td>{{ $q->sort_order }}</td>
                                    <td><strong>{{ $q->dimension }}</strong></td>
                                    <td>{{ $q->question }}@if ($q->help_text)<br><small class="text-muted">{{ $q->help_text }}</small>@endif</td>
                                    <td>{{ $q->weight }}</td>
                                    <td>{{ $q->is_active ? 'Ya' : 'Tidak' }}</td>
                                    <td class="text-nowrap"><a href="{{ route('assessments.questions.edit', [$track->id, $q->id]) }}" class="btn btn-sm btn-primary">Edit</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">Belum ada soal.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $questions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
