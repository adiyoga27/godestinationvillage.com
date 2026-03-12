<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryEvent\CategoryEventCreateRequest;
use App\Http\Requests\CategoryHomeStay\CategoryHomeStayUpdateRequest;
use App\Services\CategoryHomeStayService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class CategoryHomeStayController extends Controller
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
            return DataTables::of(CategoryHomeStayService::all())
            ->addColumn('action', function($category){
                return view('datatable._action_dinamyc', [
                    'model'           => $category,
                    'delete'          => route('category-homestay.destroy', $category->id),
                    'url'             => [
                        'Edit'            => route('category-homestay.edit', $category->id),
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $category->name . '" ?',
                    'padding'         => '85px',
                ]);
            })
            ->editColumn('is_active', function($category){
                if($category->is_active == 0)
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
              ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Kategori' ])
              ->addColumn(['data' => 'description', 'name' => 'name', 'title' => 'Description' ])

              ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Dibuat' ])
              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
                'order' => [3, 'desc']
              ]);

        return view('backend.homestay.category.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.homestay.category.create');
    }

    public function store(CategoryEventCreateRequest $request)
    {
        $result = CategoryHomeStayService::create($request->except('_token'));

        if ($result) 
            return redirect(route('category-homestay.index'))->with('status', 'Successfully created');
        else
            return redirect(route('category-homestay.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $category = CategoryHomeStayService::find($id);

        return view('backend.homestay.category.edit')->with(compact(
            'category'
        ));
    }

    public function update($id, CategoryHomeStayUpdateRequest $request)
    {
        $result = CategoryHomeStayService::update($id, $request->except('_token'));
        
        if ($result) 
            return redirect(route('category-homestay.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = CategoryHomeStayService::destroy($id);

        if ($result)
            return redirect(route('category-homestay.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('category-homestay.index'))->with('error','Failed to delete');
    }
    
}
