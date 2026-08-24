<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class SubscriberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        if (request()->ajax()) {
            \Illuminate\Support\Facades\DB::statement('set @rownum=0');
            return DataTables::of(
                Subscriber::query()->select([
                    \Illuminate\Support\Facades\DB::raw('@rownum := @rownum + 1 AS rownum'),
                    'id',
                    'email',
                    'name',
                    'is_active',
                    'created_at',
                ])
            )
                ->addColumn('action', function ($subscriber) {
                    return view('datatable._action_dinamyc', [
                        'model'           => $subscriber,
                        'delete'          => route('subscriber.destroy', $subscriber->id),
                        'url'             => [],
                        'confirm_message' => 'Anda yakin untuk menghapus subscriber "' . $subscriber->email . '" ?',
                        'padding'         => '85px',
                    ]);
                })
                ->editColumn('is_active', function ($subscriber) {
                    return $subscriber->is_active
                        ? '<span class="badge badge-success">Aktif</span>'
                        : '<span class="badge badge-secondary">Berhenti</span>';
                })
                ->editColumn('created_at', function ($subscriber) {
                    return date('Y-m-d', strtotime($subscriber->created_at));
                })
                ->rawColumns(['action', 'is_active'])
                ->toJson();
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'rownum', 'name' => 'rownum', 'title' => 'No', 'searchable' => false])
            ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email'])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama'])
            ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status'])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Berlangganan Sejak'])
            ->parameters([
                'scrollX' => true,
                'order' => [5, 'desc'],
            ]);

        return view('backend.subscriber.index')->with(compact('html'));
    }

    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        return redirect(route('subscriber.index'))->with('status', 'Subscriber berhasil dihapus');
    }

    public function export()
    {
        $subscribers = Subscriber::where('is_active', true)->orderBy('created_at', 'desc')->get();

        $csv = fopen('php://temp', 'w');
        fputcsv($csv, ['Email', 'Nama', 'Berlangganan Sejak']);

        foreach ($subscribers as $subscriber) {
            fputcsv($csv, [
                $subscriber->email,
                $subscriber->name,
                $subscriber->created_at ? $subscriber->created_at->format('Y-m-d') : '',
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers-' . date('Y-m-d') . '.csv"',
        ]);
    }
}