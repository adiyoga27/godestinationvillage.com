<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Package;

class SearchController extends Controller
{
    //
    public function searchHome(Request $request)
    {

        $data['packages'] = Package::select('packages.name', 'categories.name as cat_name','village_details.village_name as vil_name','price','packages.desc','packages.id','default_img')->join('users', 'users.id', 'user_id')->join('village_details', 'users.id', 'village_details.user_id')->join('categories', 'categories.id', 'category_id')->where('packages.name','LIKE','%'.$request->key.'%')->where('users.is_active', '1')->where('packages.is_active', '1')
                            ->paginate(16);
        return view('frontend/search',$data);
    }
}
