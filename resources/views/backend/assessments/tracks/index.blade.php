@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">Jalur Asesmen</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Administrator</li>
                <li class="breadcrumb-item active" aria-current="page">Asesmen</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('assessments.create') }}" class="btn btn-lg btn-gradient-danger mr-2">
                    <i class="mdi mdi-plus-circle-outline"></i> Tambah Jalur
                </a>
                <a href="{{ route('assessment-results.index') }}" class="btn btn-lg btn-secondary mr-2">Inbox Hasil</a>
                <br /><br />
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>#</th><th>Jalur</th><th>Slug</th><th>Soal</th><th>Hasil</th><th>Aktif</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($tracks as $t)
                                <tr>
                                    <td>{{ $t->sort_order }}</td>
                                    <td><strong>{{ $t->name }}</strong><br><small>{{ $t->tagline }}</small></td>
                                    <td><code>{{ $t->slug }}</code></td>
                                    <td>{{ $t->questions_count }}</td>
                                    <td>{{ $t->results_count }}</td>
                                    <td>{{ $t->is_active ? 'Ya' : 'Tidak' }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('assessments.questions.index', $t->id) }}" class="btn btn-sm btn-info">Soal</a>
                                        <a href="{{ route('assessments.edit', $t->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">Belum ada jalur.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
