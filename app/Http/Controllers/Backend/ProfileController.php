<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\UserService;
use App\Services\VillageService;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Session;

class ProfileController extends Controller
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
        if(Auth::user()->role_id == 1)
    	    $user = UserService::find(Auth::user()->id);
        else
            $user = VillageService::find(Auth::user()->id);
            
    	return view('backend.profile.index')->with([
            'user'=>$user,
            'village'=>$user
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        if(Auth::user()->role_id == 1)
        {
        	if (empty($request->password)) {
                $update = UserService::update_profile(Auth::user()->id, $request->except('_token', 'password', 'password_confirmation'));
            } else {
                $update = UserService::update_profile(Auth::user()->id, $request->except('_token'));
            }
        }
        else
        {
            if (empty($request->password)) 
                $user = UserService::update(Auth::user()->id, $request->only([
                    'name', 'email', 'phone', 'role_id', 'avatar', 'address', 'is_active', 'country'
                ]));
            else
                $user = UserService::update(Auth::user()->id, $request->only([
                    'name', 'email', 'password', 'password_confirmation', 'phone', 'role_id', 'avatar', 'address', 'is_active'
                ]));

            $update = VillageService::update(Auth::user()->id, $request->only([
                'village_name', 'village_address', 'lat', 'lng', 'contact_person', 'desc', 'bank_name', 'bank_acc_name', 'bank_acc_no'
            ]));
        }
    	

    	if($update){
    		return redirect('administrator/profile')->with('status', 'Successfully updated');
    	} else {
    		return back()->with('error','Failed to update');
    	}
    }

}