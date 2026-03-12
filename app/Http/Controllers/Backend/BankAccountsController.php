<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Services\BankAccountService;
use App\Http\Requests\BankAccount\BankAccountCreateRequest;
use App\Http\Requests\BankAccount\BankAccountUpdateRequest;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;



class BankAccountsController extends Controller
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
            return DataTables::of(BankAccountService::all())
            ->addColumn('action', function($bank_account){
                return view('datatable._action_dinamyc', [
                    'model'           => $bank_account,
                    'delete'          => route('bank_account.destroy', $bank_account->id),
                    'url'             => [
                        'Edit'            => route('bank_account.edit', $bank_account->id),
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $bank_account->bank_name . '" ?',
                    'padding'         => '85px',
                ]);
            })
            ->editColumn('is_active', function($bank_account){
                if($bank_account->is_active == 0)
                    return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                else
                    return "<label class='badge badge-gradient-success'>Aktif</label>";
            })->rawColumns(['action', 'is_active'])->toJson();
        }

        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'bank_name', 'name' => 'bank_name', 'title' => 'Nama Bank' ])
              ->addColumn(['data' => 'bank_acc_name', 'name' => 'bank_acc_name', 'title' => 'Nama Pemilik' ])
              ->addColumn(['data' => 'bank_acc_no', 'name' => 'bank_acc_no', 'title' => 'No. Rekening' ])
              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])
              ->parameters([
                'scrollX' => true,
              ]);

        return view('backend.bank_account.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.bank_account.create');
    }

    public function store(BankAccountCreateRequest $request)
    {
        $result = BankAccountService::create($request->except('_token'));

        if ($result) 
            return redirect(route('bank_account.index'))->with('status', 'Successfully created');
        else
            return redirect(route('bank_account.create'))->with('error', 'Failed to create');
    }

    public function edit($id)
    {
        $bank_account = BankAccountService::find($id);

        return view('backend.bank_account.edit')->with(compact(
            'bank_account'
        ));
    }

    public function update($id, BankAccountUpdateRequest $request)
    {
        $result = BankAccountService::update($id, $request->except('_token'));
        
        if ($result) 
            return redirect(route('bank_account.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = BankAccountService::destroy($id);

        if ($result)
            return redirect(route('bank_account.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('bank_account.index'))->with('error','Failed to delete');
    }

}