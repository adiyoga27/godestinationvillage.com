<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SystemLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Daftar aktivitas user yang login (system log).
     * Hanya untuk super admin (role_id = 1).
     */
    public function index(Request $request)
    {
        abort_unless((int) auth()->user()->role_id === 1, 403);

        $logs = Activity::with('causer')
            ->where('log_name', 'system')
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = '%'.$request->q.'%';
                $query->where(function ($sub) use ($q) {
                    $sub->where('description', 'like', $q)
                        ->orWhere('properties', 'like', $q);
                });
            })
            ->when($request->filled('user_id'), function ($query) use ($request) {
                $query->where('causer_id', $request->user_id);
            })
            ->when($request->filled('method'), function ($query) use ($request) {
                $query->where('description', 'like', $request->method.' %');
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $users = User::whereIn(
            'id',
            Activity::where('log_name', 'system')
                ->whereNotNull('causer_id')
                ->distinct()
                ->pluck('causer_id')
        )->orderBy('name')->get(['id', 'name']);

        return view('backend.system_log.index', compact('logs', 'users'));
    }

    /**
     * Hapus log sistem yang lebih tua dari 90 hari.
     */
    public function prune()
    {
        abort_unless((int) auth()->user()->role_id === 1, 403);

        $deleted = Activity::where('log_name', 'system')
            ->where('created_at', '<', now()->subDays(90))
            ->delete();

        return redirect()->route('system-log.index')
            ->with('status', "Berhasil menghapus {$deleted} log lama (> 90 hari).");
    }
}
