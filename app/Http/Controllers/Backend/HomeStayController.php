<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Homestay\HomestayCreateRequest;
use App\Http\Requests\Homestay\HomestayUpdateRequest;
use App\Models\CategoryHomestay;
use App\Models\HomestayTranslations;
use App\Services\CategoryHomeStayService;
use App\Services\HomeStayServices;
use App\Services\VillageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class HomeStayController extends Controller
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
            $query = HomeStayServices::all();
            if (Auth::user()->role_id == 2) {
                $query->where('homestay.village_id', Auth::user()->village_id);
            }
            return DataTables::of($query)
            ->addColumn('action', function($package){
                if (Auth::user()->role_id == 2 && !$package->is_active) {  
                    return view('datatable._action_dinamyc', [
                        'model'           => $package,
                        'delete'          => route('homestay.destroy', $package->id),
                        'url'             => [
                            'Edit'            => route('homestay.edit', $package->id),
                        ],
                        'confirm_message' =>  'Anda yakin untuk menghapus data "' . $package->name . '" ?',
                        'padding'         => '85px',
                    ]);
                } else{
                    return  view('datatable._action_dinamyc',  [
                        'model'           => $package,
                        'delete'          => route('homestay.destroy', $package->id),
                        'url'             => [
                            'Edit'            => route('homestay.edit', $package->id),
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
              ->addColumn(['data' => 'location', 'name' => 'created_at', 'title' => 'Lokasi' ])
              ->addColumn(['data' => 'price', 'name' => 'created_at', 'title' => 'price' ])
              ->addColumn(['data' => 'owner_name', 'name' => 'created_at', 'title' => 'owner' ])

              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
                'order' => [3, 'desc']
              ]);

        return view('backend.homestay.package.index')->with(compact('html'));
    }

    public function create()
    {
        $villages = VillageService::pluck()->prepend('Pilih Village', '');

        $categories = CategoryHomeStayService::pluck()->prepend('Pilih Kategori', '');
   
        return view('backend.homestay.package.create')->with(compact(
            'categories', 'villages'
        ));
    }

    public function store(HomestayCreateRequest $request)
    {
        $result = HomeStayServices::create($request->except('_token'));

        if ($result) 
            return redirect(route('homestay.index'))->with('status', 'Successfully created');
        else
            return redirect(route('homestay.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $package = HomeStayServices::find($id);
        $packageTranslate = HomestayTranslations::where('homestay_id', $id)->first();
        $villages = VillageService::pluck();

        $categories = CategoryHomeStayService::pluck();
        return view('backend.homestay.package.edit')->with(compact(
            'categories',
            'package',
            'packageTranslate', 
            'villages'
        ));
    }

    public function update($id, HomestayUpdateRequest $request)
    {
        $result = HomeStayServices::update($id, $request->except('_token'));
        
        if ($result) 
            return redirect(route('homestay.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = HomeStayServices::destroy($id);

        if ($result)
            return redirect(route('homestay.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('homestay.index'))->with('error','Failed to delete');
    }
}
