<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\CategoryService;
use App\Services\VillageService;
use App\Services\PackageService;
use App\Services\OrderService;
use App\Http\Requests\Package\PackageCreateRequest;
use App\Http\Requests\Package\PackageUpdateRequest;
use App\Models\PackageTranslations;
use App\Services\TagServices as ServicesTagServices;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PackagesController extends Controller
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
        $allowed = true;
        if (request()->ajax()) {
            $query = PackageService::all();
            if (Auth::user()->role_id == 2) {
                $query->where('packages.village_id', Auth::user()->village_id);
            }
            return DataTables::of($query)
                ->addColumn('action', function ($package) {
                    if (Auth::user()->role_id == 2 && !$package->is_active) {
                       
                    return  view('datatable._action_dinamyc',  [
                        'model'           => $package,
                        'delete'          => route('package.destroy', $package->id),
                        'url'             => [
                            'Edit'            => route('package.edit', $package->id),
                            // 'Show'            => route('package.show', $package->id),
                        ] ,
                        'confirm_message' =>  'Anda yakin untuk menghapus data "' . $package->name . '" ?',
                        'padding'         => '85px',
                    ]);
                }else{
                    return  view('datatable._action_dinamyc',  [
                        'model'           => $package,
                        'delete'          => route('package.destroy', $package->id),
                        'url'             => [
                            'Edit'            => route('package.edit', $package->id),
                            // 'Show'            => route('package.show', $package->id),
                        ] ,
                        'confirm_message' =>  'Anda yakin untuk menghapus data "' . $package->name . '" ?',
                        'padding'         => '85px',
                    ]);
                }

                })
                ->editColumn('is_active', function ($package) {
                    if (Auth::user()->role_id == 1) {
                        if ($package->is_active == 0)
                            return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                        else
                            return "<label class='badge badge-gradient-success'>Aktif</label>";
                    }else{
                        if ($package->is_active == 0)
                            return "<label class='badge badge-gradient-danger'>Proses Validasi</label>";
                        else
                            return "<label class='badge badge-gradient-success'>Aktif</label>";
                    }
                })
                ->editColumn('price', function ($package) {
                    return 'Rp. ' . number_format($package->price, 2, '.', ',');
                })->editColumn('created_at', function ($member) {
                    return date('Y-m-d', strtotime($member->created_at));
                })->rawColumns(['action', 'is_active'])->toJson();
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'rownum', 'name' => 'rownum', 'title' => 'No', 'searchable' => false])
            ->addColumn(['data' => 'village_name', 'name' => 'village_details.village_name', 'title' => 'Desa Wisata'])
            ->addColumn(['data' => 'category_name', 'name' => 'categories.name', 'title' => 'Kategori'])
            ->addColumn(['data' => 'name', 'name' => 'name', 'title' => 'Nama Paket'])
            ->addColumn(['data' => 'price', 'name' => 'price', 'title' => 'Harga Paket'])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Dibuat'])
            ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status'])
            ->parameters([
                'scrollX' => true,
                'order' => [6, 'desc']
            ]);

        return view('backend.package.index')->with(compact('html'));
    }

    public function get_orders($id)
    {
        return Datatables::of(PackageService::find_by_user($id))
            ->editColumn('package_price', function ($order) {
                return number_format($order->package_price);
            })
            ->editColumn('package_discount', function ($order) {
                return number_format($order->package_discount);
            })
            ->editColumn('total_payment', function ($order) {
                return number_format($order->total_payment);
            })
            ->editColumn('payment_type', function ($order) {
                return str_replace('_', '', strtoupper($order->payment_type));
            })
            ->editColumn('payment_status', function ($order) {
                if ($order->payment_status == 'pending')
                    return "<label class='badge badge-gradient-warning'>Pending</label>";
                elseif ($order->payment_status == 'success')
                    return "<label class='badge badge-gradient-success'>Sukses</label>";
                else
                    return "<label class='badge badge-gradient-danger'>Cancel</label>";
            })
            ->editColumn('created_at', function ($order) {
                return date('Y-m-d h:i:s', strtotime($order->created_at));
            })->rawColumns(['payment_status'])->make(true);
    }

    public function create()
    {
        $categories = CategoryService::pluck()->prepend('Pilih Kategori', '');
        $villages = VillageService::pluck()->prepend('Pilih Desa Wisata', '');
        $tags = ServicesTagServices::pluck()->prepend('Pilih Tags', '');

        return view('backend.package.create')->with(compact(
            'categories',
            'villages',
            'tags'
        ));
    }

    public function store(PackageCreateRequest $request)
    {
        $primaryTable =  $request->except(['_token']);
        $result = PackageService::create($primaryTable);


        if ($result)
            return redirect(route('package.index'))->with('status', 'Successfully created');
        else
            return redirect(route('package.create'))->with('error', 'Failed to create');
    }

    public function show($id, Request $request, Builder $htmlBuilder)
    {
        $package = PackageService::find_with($id);
        $images = Storage::files('packages/' . $id);

        if (request()->ajax()) {
            return Datatables::of(OrderService::find_by_package($id))
                ->addColumn('action', function ($order) {
                    return "<a href='" . route('orders.show', $order->id) . "' class='btn btn-sm btn-outline-primary'>Show</a>";
                })
                ->editColumn('package_price', function ($order) {
                    return number_format($order->package_price);
                })
                ->editColumn('package_discount', function ($order) {
                    return number_format($order->package_discount);
                })
                ->editColumn('total_payment', function ($order) {
                    return number_format($order->total_payment);
                })
                ->editColumn('payment_type', function ($order) {
                    return str_replace('_', '', strtoupper($order->payment_type));
                })
                ->editColumn('payment_status', function ($order) {
                    if ($order->payment_status == 'pending')
                        return "<label class='badge badge-gradient-warning'>Pending</label>";
                    elseif ($order->payment_status == 'success')
                        return "<label class='badge badge-gradient-success'>Sukses</label>";
                    else
                        return "<label class='badge badge-gradient-danger'>Cancel</label>";
                })
                ->editColumn('created_at', function ($order) {
                    return date('Y-m-d', strtotime($order->created_at));
                })->rawColumns(['payment_status', 'action'])->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'rownum', 'name' => 'rownum', 'title' => 'No', 'searchable' => false])
            ->addColumn(['data' => 'code', 'name' => 'code', 'title' => 'No. Order'])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Dibuat'])
            ->addColumn(['data' => 'package_name', 'name' => 'package_name', 'title' => 'Nama Paket'])
            // ->addColumn(['data' => 'package_price', 'name' => 'package_price', 'title' => 'Harga Paket' ])
            // ->addColumn(['data' => 'package_discount', 'name' => 'package_discount', 'title' => 'Diskon' ])
            ->addColumn(['data' => 'total_payment', 'name' => 'total_payment', 'title' => 'Total Pembayaran'])
            ->addColumn(['data' => 'payment_type', 'name' => 'payment_type', 'title' => 'Metode Pembayaran'])
            ->addColumn(['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Status Pembayaran'])
            ->parameters([
                'scrollX' => true,
            ]);

        return view('backend.package.show')->with(compact(
            'package',
            'html',
            'images'
        ));
    }

    public function edit($id)
    {
        $package = PackageService::find($id);
        $packageTranslate = PackageTranslations::where('package_id', $id)->first();

        $categories = CategoryService::pluck();
        $villages = VillageService::pluck();
        $tags = ServicesTagServices::pluck();

        return view('backend.package.edit')->with(compact(
            'package',
            'categories',
            'villages',
            'packageTranslate',
            'tags'
        ));
    }

    public function update($id, PackageUpdateRequest $request)
    {
        $primaryTable =  $request->except(['_token']);
        $result = PackageService::update($id, $primaryTable);


        if ($result)
            return redirect(route('package.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error', 'Failed to update');
    }

    public function destroy($id)
    {
        $result = PackageService::destroy($id);

        if ($result)
            return redirect(route('package.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('package.index'))->with('error', 'Failed to delete');
    }

    public function delete_image(Request $request)
    {
        Storage::delete($request->file);
        return back()->with('status', 'Successfully deleted');
    }
}
