<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Support\Seo;

class SearchController extends Controller
{
    public function searchHome(Request $request)
    {
        $keyword = trim($request->key ?? '');
        $data['keyword'] = $keyword;
        $data['packages'] = Package::select('packages.name', 'categories.name as cat_name', 'village_details.village_name as vil_name', 'price', 'packages.desc', 'packages.id', 'default_img', 'packages.slug')
            ->with('translate')
            ->leftJoin('village_details', 'village_details.id', '=', 'packages.village_id')
            ->leftJoin('users', 'users.id', '=', 'village_details.user_id')
            ->leftJoin('categories', 'categories.id', '=', 'packages.category_id')
            ->where('packages.name', 'LIKE', '%' . $keyword . '%')
            ->where('users.is_active', '1')
            ->where('packages.is_active', '1')
            ->paginate(16)
            ->appends(['key' => $keyword]);
        $data['seo'] = Seo::make()
            ->title(__('Search Results') . ($keyword ? ': ' . $keyword : ''))
            ->description('Search results for village tourism packages, tours and experiences across Indonesia on GODEVI.')
            ->canonical('/search')
            ->noindex()
            ->toArray();
        return view('customer/search', $data);
    }
}