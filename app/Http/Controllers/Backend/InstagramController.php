<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\InstagramServices;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class InstagramController extends Controller
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
            return DataTables::of(InstagramServices::all())
            ->addColumn('action', function($instagram){
                return view('datatable._action_dinamyc', [
                    'model'           => $instagram,
                    'delete'          => route('instagram.destroy', $instagram->id),
                    'url'             => [
                        'Edit'            => route('instagram.edit', $instagram->id),
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $instagram->name . '" ?',
                    'padding'         => '85px',
                ]);
            })
            ->editColumn('is_active', function($instagram){
                if($instagram->is_active == 0)
                    return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                else
                    return "<label class='badge badge-gradient-success'>Aktif</label>";
            })->editColumn('created_at', function($admin){
                return date('Y-m-d', strtotime($admin->created_at));
            })->rawColumns(['action', 'is_active'])->toJson();
        }

        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama' ])
              ->addColumn(['data' => 'url', 'name' => 'url', 'title' => 'Link' ])
              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
                'order' => [3, 'desc']
              ]);

        return view('backend.instagram.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.instagram.create');
    }

    public function store(Request $request)
    {
        $result = InstagramServices::create($request->except('_token'));

        if ($result) 
            return redirect(route('instagram.index'))->with('status', 'Successfully created');
        else
            return redirect(route('instagram.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $instagram = InstagramServices::find($id);

        return view('backend.instagram.edit')->with(compact(
            'instagram'
        ));
    }

    public function update($id, Request $request)
    {
        $result = InstagramServices::update($id, $request->except('_token'));
        
        if ($result) 
            return redirect(route('instagram.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = InstagramServices::destroy($id);

        if ($result)
            return redirect(route('instagram.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('instagram.index'))->with('error','Failed to delete');
    }
}
