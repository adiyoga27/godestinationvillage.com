<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\DiscountMemberService;
use App\Http\Requests\DiscountMember\DiscountMemberCreateRequest;
use App\Http\Requests\DiscountMember\DiscountMemberUpdateRequest;

use Yajra\DataTables\Html\Builder;

use Session;
use Auth;
use Yajra\DataTables\Facades\DataTables;

class DiscountMembersController extends Controller
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
            return DataTables::of(DiscountMemberService::all())
            ->addColumn('action', function($discount_member){
                return view('datatable._action_dinamyc', [
                    'model'           => $discount_member,
                    'delete'          => route('discount_member.destroy', $discount_member->id),
                    'url'             => [
                        'Edit'            => route('discount_member.edit', $discount_member->id),
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $discount_member->type . '" ?',
                    'padding'         => '85px',
                ]);
            })
            ->editColumn('is_active', function($discount_member){
                if($discount_member->is_active == 0)
                    return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                else
                    return "<label class='badge badge-gradient-success'>Aktif</label>";
            })
            ->editColumn('value', function($discount_member){
                if($discount_member->type == 'fix')
                    return number_format($discount_member->value);
                else
                    return $discount_member->value . '%';
            })
            ->editColumn('type', function($discount_member){
                return strtoupper($discount_member->type);
            })->editColumn('created_at', function($admin){
                return date('Y-m-d', strtotime($admin->created_at));
            })->rawColumns(['action', 'is_active'])->toJson();
        }

        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'type', 'name' => 'type', 'title' => 'Tipe' ])
              ->addColumn(['data' => 'value', 'name' => 'value', 'title' => 'Nilai/Jumlah' ])
              ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Dibuat' ])
              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
                'order' => [4, 'desc']
              ]);

        return view('backend.discount_member.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.discount_member.create');
    }

    public function store(DiscountMemberCreateRequest $request)
    {
        $result = DiscountMemberService::create($request->except('_token'));

        if ($result) 
            return redirect(route('discount_member.index'))->with('status', 'Successfully created');
        else
            return redirect(route('discount_member.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $discount_member = DiscountMemberService::find($id);

        return view('backend.discount_member.edit')->with(compact(
            'discount_member'
        ));
    }

    public function update($id, DiscountMemberUpdateRequest $request)
    {
        $result = DiscountMemberService::update($id, $request->except('_token'));
        
        if ($result) 
            return redirect(route('discount_member.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error', 'Failed to update');
    }

    public function destroy($id)
    {  
        $result = DiscountMemberService::destroy($id);

        if ($result)
            return redirect(route('discount_member.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('discount_member.index'))->with('error', 'Anda tidak dapat menghapus discount yang statusnya AKTIF');
    }

}