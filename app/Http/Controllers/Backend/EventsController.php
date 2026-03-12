<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\EventCreateRequest;
use App\Http\Requests\Events\EventUpdateRequest;
use App\Models\EventTranslations;
use App\Models\PackageTranslations;
use App\Services\CategoryEventService;
use App\Services\EventService;
use App\Services\VillageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class EventsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if (request()->ajax()) {
            $query = EventService::all();
            $role = Auth::user()->role_id ;
            if ($role == 2) {
                $query->where('events.village_id', Auth::user()->village_id);
            }
            return DataTables::of($query)
            ->addColumn('action', function($package){
                if (Auth::user()->role_id == 2 && !$package->is_active) {
                    return view('datatable._action_dinamyc', [
                        'model'           => $package,
                        'delete'          => route('events.destroy', $package->id),
                        'url'             => [
                            'Edit'            => route('events.edit', $package->id),
                        ],
                        'confirm_message' =>  'Anda yakin untuk menghapus data "' . $package->name . '" ?',
                        'padding'         => '85px',
                    ]);
                } else{
                    return  view('datatable._action_dinamyc',  [
                        'model'           => $package,
                        'delete'          => route('events.destroy', $package->id),
                        'url'             => [
                            'Edit'            => route('events.edit', $package->id),
                            // 'Show'            => route('package.show', $package->id),
                        ] ,
                        'confirm_message' =>  'Anda yakin untuk menghapus data "' . $package->name . '" ?',
                        'padding'         => '85px',
                    ]);
                }
            })
            ->editColumn('is_active', function($package){
                if (Auth::user()->role_id == 1) {
                    if ($package->is_active == 0)
                        return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                    else
                        return "<label class='badge badge-gradient-success'>Aktif</label>";
                }else{
                    if ($package->is_active == 0)
                        return "<label class='badge badge-gradient-danger'>Proses Validasi</label>";
                    else
                        return "<label class='badge badge-gradient-success'>Aktif</label>";
                }
            })->editColumn('created_at', function($admin){
                return date('Y-m-d', strtotime($admin->created_at));
            })->rawColumns(['action', 'is_active'])->toJson();
        }
        
        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Event' ])
              ->addColumn(['data' => 'description', 'name' => 'created_at', 'title' => 'desc' ])
              ->addColumn(['data' => 'price', 'name' => 'created_at', 'title' => 'price' ])
              ->addColumn(['data' => 'date_event', 'name' => 'created_at', 'title' => 'date_event' ])

              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
                'order' => [3, 'desc']
              ]);

        return view('backend.events.package.index')->with(compact('html'));
    }

    public function create()
    {
        $villages = VillageService::pluck()->prepend('Pilih Village', '');

        $categories = CategoryEventService::pluck()->prepend('Pilih Kategori', '');
   
        return view('backend.events.package.create')->with(compact(
            'categories', 'villages'
        ));
    }

    public function store(EventCreateRequest $request)
    {
        $result = EventService::create($request->except('_token'));

        if ($result) 
            return redirect(route('events.index'))->with('status', 'Successfully created');
        else
            return redirect(route('events.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $package = EventService::find($id);
        $packageTranslate = EventTranslations::where('events_id', $id)->first();
        $villages = VillageService::pluck();

        $categories = CategoryEventService::pluck();
        return view('backend.events.package.edit')->with(compact(
            'categories',
            'package',
            'packageTranslate',
            'villages'
        ));
    }

    public function update($id, EventUpdateRequest $request)
    {
        $result = EventService::update($id, $request->except('_token'));
        
        if ($result) 
            return redirect(route('events.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = EventService::destroy($id);

        if ($result)
            return redirect(route('events.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('events.index'))->with('error','Failed to delete');
    }
}
