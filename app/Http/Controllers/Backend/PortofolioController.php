<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CustomImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\EventCreateRequest;
use App\Http\Requests\Events\EventUpdateRequest;
use App\Models\BoardExpert;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
class PortofolioController extends Controller
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
            $query = Portofolio::all();
           
            return DataTables::of($query)
            ->addColumn('action', function($res){
                    return view('datatable._action_dinamyc', [
                        'model'           => $res,
                        'delete'          => route('portofolio.destroy', $res->id),
                        'url'             => [
                            'Edit'            => route('portofolio.edit', $res->id),
                        ],
                        'confirm_message' =>  'Anda yakin untuk menghapus data "' . $res->title . '" ?',
                        'padding'         => '85px',
                    ]);
          
            })
          ->editColumn('created_at', function($admin){
                return date('Y-m-d', strtotime($admin->created_at));
            })->rawColumns(['action', 'is_active'])->toJson();
        }
        
        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'title', 'name' => 'title', 'title' => 'title' ])
              ->addColumn(['data' => 'dates', 'name' => 'dates', 'title' => 'dates' ])
              ->addColumn(['data' => 'description', 'name' => 'description', 'title' => 'description' ])
              ->parameters([
                'scrollX' => true,
                'order' => [3, 'desc']
              ]);

        return view('backend.protofolio.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.protofolio.create');
    }

    public function store(EventCreateRequest $request)
    {
        $payload =$request->except('_token');

        if (!empty($payload['attachment'])) {
            $upload = CustomImage::storeImage($payload['attachment'], 'portofolio');
            $payload['attachment'] = $upload['name'];
        }
        if (!empty($payload['thumbnail'])) {
            $uploadPortofolio = CustomImage::storeImage($payload['thumbnail'], 'portofolio/thumbnail');
            $payload['thumbnail'] = $uploadPortofolio['name'];
        }
        $result = Portofolio::create($payload);

        if ($result) 
            return redirect(route('portofolio.index'))->with('status', 'Successfully created');
        else
            return redirect(route('portofolio.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $portofolio = Portofolio::find($id);

        return view('backend.protofolio.edit')->with(compact(
            'portofolio',
        ));
    }

    public function update($id, EventUpdateRequest $request)
    {
        $payload =$request->except(['_token','_method']);

        if (!empty($payload['attachment'])) {
            $upload = CustomImage::storeImage($payload['attachment'], 'portofolio');
            $payload['attachment'] = $upload['name'];
        }
        if (!empty($payload['thumbnail'])) {
            $uploadPortofolio = CustomImage::storeImage($payload['thumbnail'], 'portofolio/thumbnail');
            $payload['thumbnail'] = $uploadPortofolio['name'];
        }
        $result = Portofolio::where('id', $id)->update($payload);
        
        if ($result) 
            return redirect(route('portofolio.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = Portofolio::destroy($id);

        if ($result)
            return redirect(route('portofolio.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('portofolio.index'))->with('error','Failed to delete');
    }
}
