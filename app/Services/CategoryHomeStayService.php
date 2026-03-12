<?php

namespace App\Services;

use App\Models\CategoryHomestay;
use Illuminate\Support\Facades\DB;

class CategoryHomeStayService  
{
    public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return CategoryHomestay::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('category_homestay.*')
        ]);

    }

    public static function find($id)
    {
        return CategoryHomestay::find($id);
    }

    public static function create($payload)
    {
        $model = CategoryHomestay::create($payload);
        return $model;
    }

    public static function update($id, $payload)
    {
        $model = CategoryHomestay::find($id);
        return $model->update($payload);
    }

    public static function destroy($id)
    {
        $model = CategoryHomestay::find($id);
        return $model->destroy($id);
    }

    public static function pluck()
    {
        return CategoryHomestay::where('is_active', 1)->pluck('name', 'id');
    }
}
