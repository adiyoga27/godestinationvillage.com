<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\UserService;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;

use Yajra\DataTables\Html\Builder;

use Session;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class AdminsController extends Controller
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
            return DataTables::of(UserService::get_by_role(1))
            ->addColumn('action', function($admin){
                return view('datatable._action_dinamyc', [
                    'model'           => $admin,
                    'delete'          => route('user_admin.destroy', $admin->id),
                    'url'             => [
                        'Edit'            => route('user_admin.edit', $admin->id),
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $admin->name . '" ?',
                    'padding'         => '85px',
                ]);
            })
            ->editColumn('is_active', function($admin){
                if($admin->is_active == 0)
                    return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                else
                    return "<label class='badge badge-gradient-success'>Aktif</label>";
            })
            ->editColumn('created_at', function($admin){
                return date('Y-m-d', strtotime($admin->created_at));
            })->rawColumns(['action', 'is_active'])->toJson();
        }

        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama' ])
              ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email' ])
              ->addColumn(['data' => 'phone', 'name' => 'phone', 'title' => 'No. Telp' ])
              ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Dibuat' ])
              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
                'order' => [5, 'desc']
              ]);

        return view('backend.admin.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.admin.create');
    }

    public function store(UserCreateRequest $request)
    {
        $result = UserService::create($request->except('_token'));

        if ($result) 
            return redirect(route('user_admin.index'))->with('status', 'Successfully created');
        else
            return redirect(route('user_admin.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $admin = UserService::find($id);

        return view('backend.admin.edit')->with(compact(
            'admin'
        ));
    }

    public function update($id, UserUpdateRequest $request)
    {
        if (empty($request->password)) {
            $result = UserService::update($id, $request->except('_token', 'password'));
        } else {
            $result = UserService::update($id, $request->except('_token'));
        }
        
        if ($result) 
            return redirect(route('user_admin.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = UserService::destroy($id);

        if ($result)
            return redirect(route('user_admin.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('user_admin.index'))->with('error','Failed to delete');
    }

}