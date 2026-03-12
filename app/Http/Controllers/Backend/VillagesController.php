<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\UserService;
use App\Services\VillageService;

use App\Http\Requests\Village\VillageCreateRequest;
use App\Http\Requests\Village\VillageUpdateRequest;

use Yajra\DataTables\Html\Builder;

use Session;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class VillagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if (request()->ajax()) {
            return DataTables::of(VillageService::get_by_role(2))
            ->addColumn('action', function($village){
                return view('datatable._action_dinamyc', [
                    'model'           => $village,
                    'delete'          => route('user_village.destroy', $village->id),
                    'url'             => [
                        'Edit'            => route('user_village.edit', $village->id),
                        'Show'            => route('user_village.show', $village->id)
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $village->village_name . '" ?',
                    'padding'         => '85px',
                ]);
            })
            ->editColumn('is_active', function($village){
                if($village->is_active == 0)
                    return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                else
                    return "<label class='badge badge-gradient-success'>Aktif</label>";
            })
      
            ->editColumn('created_at', function($village){
                return date('Y-m-d', strtotime($village->created_at));
            })->rawColumns(['action', 'is_active'])->toJson();
        }

        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'village_name', 'name' => 'village_name', 'title' => 'Nama Desa Wisata'])
              ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Pengguna' ])
              ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email' ])
              ->addColumn(['data' => 'phone', 'name' => 'phone', 'title' => 'No. Telp' ])
              ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Dibuat' ])
              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
                'order' => [6, 'desc']
              ]);

        return view('backend.village.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.village.create');
    }

    public function store(VillageCreateRequest $request)
    {
        $user = UserService::create($request->only([
            'name', 'email', 'password', 'password_confirmation', 'phone', 'role_id', 'avatar', 'address', 'is_active', 'country'
        ]));

        $result = VillageService::create($user->id, $request->only([
            'village_name', 'village_address', 'lat', 'lng', 'contact_person', 'desc', 'bank_name', 'bank_acc_name', 'bank_acc_no'
        ]));

        if ($result) 
            return redirect(route('user_village.index'))->with('status', 'Successfully created');
        else
            return redirect(route('user_village.create'))->with('error', 'Failed to create');
    }

    public function show($id)
    {
        $village = VillageService::find_with($id);

        return view('backend.village.show')->with(compact(
            'village'
        ));
    }

    public function edit($id)
    {
        $village = VillageService::find($id);

        return view('backend.village.edit')->with(compact(
            'village'
        ));
    }

    public function update($id, VillageUpdateRequest $request)
    {
        if (empty($request->password)) 
            $user = UserService::update($id, $request->only([
                'name', 'email', 'phone', 'role_id', 'avatar', 'address', 'is_active', 'country'
            ]));
        else
            $user = UserService::update($id, $request->only([
                'name', 'email', 'password', 'password_confirmation', 'phone', 'role_id', 'avatar', 'address', 'is_active'
            ]));

        $result = VillageService::update($id, $request->only([
            'village_name', 'village_address', 'lat', 'lng', 'contact_person', 'desc', 'bank_name', 'bank_acc_name', 'bank_acc_no'
        ]));
        
        if ($result) 
            return redirect(route('user_village.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = VillageService::destroy($id);
        $user = UserService::destroy($id);

        if ($result)
            return redirect(route('user_village.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('user_village.index'))->with('error','Failed to delete');
    }

}