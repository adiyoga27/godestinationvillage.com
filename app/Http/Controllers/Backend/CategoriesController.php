<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\CategoryService;
use App\Http\Requests\Category\CategoryCreateRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;

use Yajra\DataTables\Html\Builder;

use Session;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class CategoriesController extends Controller
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
            return DataTables::of(CategoryService::all())
            ->addColumn('action', function($category){
                return view('datatable._action_dinamyc', [
                    'model'           => $category,
                    'delete'          => route('category.destroy', $category->id),
                    'url'             => [
                        'Edit'            => route('category.edit', $category->id),
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
              ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Dibuat' ])
              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
                'order' => [3, 'desc']
              ]);

        return view('backend.category.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.category.create');
    }

    public function store(CategoryCreateRequest $request)
    {
        $result = CategoryService::create($request->except('_token'));

        if ($result) 
            return redirect(route('category.index'))->with('status', 'Successfully created');
        else
            return redirect(route('category.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $category = CategoryService::find($id);

        return view('backend.category.edit')->with(compact(
            'category'
        ));
    }

    public function update($id, CategoryUpdateRequest $request)
    {
        $result = CategoryService::update($id, $request->except('_token'));
        
        if ($result) 
            return redirect(route('category.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = CategoryService::destroy($id);

        if ($result)
            return redirect(route('category.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('category.index'))->with('error','Failed to delete');
    }

}