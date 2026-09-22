@extends('layouts.backend')

@section('content-header')
    <div class="page-header">
        <h3 class="page-title">
          Log Sistem
        </h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">Administrator</li>
            <li class="breadcrumb-item active" aria-current="page">Log Sistem</li>
          </ol>
        </nav>
      </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-description">
                    Seluruh aktivitas user yang login tercatat di sini: halaman yang dibuka,
                    data yang dibuat/diubah/dihapus, beserta waktu, IP, dan user agent.
                </p>

                <form method="GET" action="{{ route('system-log.index') }}" class="form-inline mb-3">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-2 mb-2"
                        placeholder="Cari aktivitas / URL / IP..." style="min-width: 240px;">
                    <select name="user_id" class="form-control form-control-sm mr-2 mb-2">
                        <option value="">-- Semua User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="method" class="form-control form-control-sm mr-2 mb-2">
                        <option value="">-- Semua Method --</option>
                        @foreach (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $method)
                            <option value="{{ $method }}" {{ request('method') === $method ? 'selected' : '' }}>
                                {{ $method }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-gradient-primary mr-2 mb-2">
                        <i class="mdi mdi-magnify"></i> Filter
                    </button>
                    <a href="{{ route('system-log.index') }}" class="btn btn-sm btn-light mb-2">Reset</a>

                    <span class="ml-auto mb-2">
                        <button type="submit" form="prune-form" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Hapus semua log yang lebih tua dari 90 hari?')">
                            <i class="mdi mdi-delete-sweep"></i> Bersihkan Log &gt; 90 Hari
                        </button>
                    </span>
                </form>

                <form id="prune-form" method="POST" action="{{ route('system-log.prune') }}" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>

                <div class="table-responsive">
                    <table class="table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Waktu</th>
                                <th>User</th>
                                <th>Aktivitas</th>
                                <th>Route</th>
                                <th>IP</th>
                                <th>User Agent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                @php
                                    $props = $log->properties ?? collect();
                                    $method = strtoupper(is_array($props) ? ($props['method'] ?? '') : ($props->get('method') ?? ''));
                                    $badge = match ($method) {
                                        'POST' => 'badge-info',
                                        'PUT', 'PATCH' => 'badge-warning',
                                        'DELETE' => 'badge-danger',
                                        default => 'badge-success',
                                    };
                                @endphp
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td style="white-space: nowrap;">{{ $log->created_at?->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                        {{ $log->causer?->name ?? '-' }}
                                        @if ($log->causer)
                                            <br><small class="text-muted">{{ $log->causer->email }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $badge }}">{{ $method ?: '?' }}</span>
                                        <code>{{ $log->description }}</code>
                                        @php
                                            $params = is_array($props) ? ($props['route_params'] ?? []) : ($props->get('route_params') ?? []);
                                            $input = is_array($props) ? ($props['input'] ?? []) : ($props->get('input') ?? []);
                                        @endphp
                                        @if (! empty($params) || ! empty($input))
                                            <br><small class="text-muted">{{ \Illuminate\Support\Str::limit(json_encode(['params' => $params, 'input' => $input]), 160) }}</small>
                                        @endif
                                    </td>
                                    <td><small>{{ is_array($props) ? ($props['route'] ?? '-') : ($props->get('route') ?? '-') }}</small></td>
                                    <td><small>{{ is_array($props) ? ($props['ip'] ?? '-') : ($props->get('ip') ?? '-') }}</small></td>
                                    <td><small>{{ \Illuminate\Support\Str::limit(is_array($props) ? ($props['user_agent'] ?? '-') : ($props->get('user_agent') ?? '-'), 60) }}</small></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada aktivitas tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logs->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
