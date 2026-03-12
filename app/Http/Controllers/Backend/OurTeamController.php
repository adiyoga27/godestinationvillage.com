<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CustomImage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\EventCreateRequest;
use App\Http\Requests\Events\EventUpdateRequest;
use App\Models\OurTeam;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
class OurTeamController extends Controller
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
            $query = OurTeam::all();
           
            return DataTables::of($query)
            ->addColumn('action', function($res){
                    return view('datatable._action_dinamyc', [
                        'model'           => $res,
                        'delete'          => route('ourteam.destroy', $res->id),
                        'url'             => [
                            'Edit'            => route('ourteam.edit', $res->id),
                        ],
                        'confirm_message' =>  'Anda yakin untuk menghapus data "' . $res->name . '" ?',
                        'padding'         => '85px',
                    ]);
          
            })
          ->editColumn('created_at', function($admin){
                return date('Y-m-d', strtotime($admin->created_at));
            })->rawColumns(['action', 'is_active'])->toJson();
        }
        
        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Name' ])
              ->addColumn(['data' => 'title', 'name' => 'title', 'title' => 'title' ])
              ->addColumn(['data' => 'phone', 'name' => 'phone', 'title' => 'phone' ])
              ->addColumn(['data' => 'whatsapp', 'name' => 'whatsapp', 'title' => 'whatsapp' ])
              ->parameters([
                'scrollX' => true,
                'order' => [3, 'desc']
              ]);

        return view('backend.ourteam.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.ourteam.create');
    }

    public function store(EventCreateRequest $request)
    {
        $payload =$request->except('_token');

        if (!empty($payload['avatar'])) {
            $upload = CustomImage::storeImage($payload['avatar'], 'ourteam');
            $payload['avatar'] = $upload['name'];
        }
        $result = OurTeam::create($payload);

        if ($result) 
            return redirect(route('ourteam.index'))->with('status', 'Successfully created');
        else
            return redirect(route('ourteam.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $ourteam = OurTeam::find($id);

        return view('backend.ourteam.edit')->with(compact(
            'ourteam',
        ));
    }

    public function update($id, EventUpdateRequest $request)
    {
        $payload =$request->except(['_token','_method']);

        if (!empty($payload['avatar'])) {
            $upload = CustomImage::storeImage($payload['avatar'], 'ourteam');
            $payload['avatar'] = $upload['name'];
        }

        $result = OurTeam::where('id', $id)->update($payload);
        
        if ($result) 
            return redirect(route('ourteam.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = OurTeam::destroy($id);

        if ($result)
            return redirect(route('ourteam.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('ourteam.index'))->with('error','Failed to delete');
    }
}
