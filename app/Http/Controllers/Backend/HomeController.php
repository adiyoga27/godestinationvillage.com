<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;

use App\Services\UserService;
use App\Services\PackageService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
    public function index()
    {
      
        $count_admin = UserService::count_by_role(1);
        $count_village = UserService::count_by_role(2);
        $count_member = UserService::count_by_role(3);

        if(Auth::user()->role_id == 1)
        {
            $count_package = PackageService::count();
            $count_order = OrderService::count();
            $sum_order = OrderService::income();
        }
        else
        {
            $count_package = PackageService::count(Auth::user()->id);
            $count_order = OrderService::count(Auth::user()->id);
            $sum_order = OrderService::income(Auth::user()->id);
        }

        return view('backend.dashboard')->with(compact(
            'count_admin',
            'count_village',
            'count_member',
            'count_package',
            'count_order',
            'sum_order'
        ));
    }
}
