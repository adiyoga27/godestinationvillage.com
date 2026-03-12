<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Certificate\CertificateCreateRequest;
use App\Http\Requests\Certificate\CertificatUpdateRequest;
use App\Services\CertificationServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class CertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
            $query = CertificationServices::all();
            $role = Auth::user()->role_id ;
            if ($role == 2) {
                $query->where('certifiaction.reference_number', Auth::user()->id);
            }
            return DataTables::of($query)
            ->addColumn('action', function($certification){
                return view('datatable._action_dinamyc', [
                    'model'           => $certification,
                    'delete'          => route('surat.destroy', $certification->id),
                    'url'             => [
                        'Edit'            => route('surat.edit', $certification->id),
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $certification->reference_number . '" ?',
                    'padding'         => '85px',
                ]);
            })
        
            ->editColumn('slug', function($certification){
                return  url('surat/'.$certification->slug);
           
            })->editColumn('qr', function($certification){
                    return url('storage/certification/qr-code/qr-'.$certification->slug.".png");
               
            })
            ->editColumn('isActive', function($certification){
                if($certification->isActive == 0)
                    return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                else
                    return "<label class='badge badge-gradient-success'>Aktif</label>";
            })->rawColumns(['action', 'isActive'])->toJson();
        }
        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'category', 'name' => 'category', 'title' => 'Kategori' ])
              
              ->addColumn(['data' => 'reference_number', 'name' => 'reference_number', 'title' => 'No Surat' ])
              ->addColumn(['data' => 'addressed_to', 'name' => 'addressed_to', 'title' => 'Ditunjukan Kepada' ])
              ->addColumn(['data' => 'slug', 'name' => 'slug', 'title' => 'Link Surat' ])
              ->addColumn(['data' => 'qr', 'name' => 'qr', 'title' => 'Qr' ])

              ->addColumn(['data' => 'isActive', 'name' => 'isActive', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
              ]);
        return view('backend.certification.index')->with(compact('html'));
    }
    public function create()
    {
        return view('backend.certification.create');
    }
    public function store(CertificateCreateRequest $request)
    {
        $result = CertificationServices::create($request->except('_token'));
        if ($result) 
            return redirect(route('surat.index'))->with('status', 'Successfully created');
        else
            return redirect(route('surat.create'))->with('error', 'Failed to create');
    }
    public function edit($id)
    {
        $certification = CertificationServices::find($id);
        return view('backend.certification.edit')->with(compact(
            'certification'
        ));
    }
    public function update($id, CertificatUpdateRequest $request)
    {
        $result = CertificationServices::update($id, $request->except('_token'));
        if ($result) 
            return redirect(route('surat.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }
    public function destroy($id)
    {  
        $result = CertificationServices::destroy($id);
        if ($result)
            return redirect(route('surat.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('surat.index'))->with('error','Failed to delete');
    }

    public function surat($id)
    {
        # code...
    }
}
