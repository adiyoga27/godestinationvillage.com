<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class CategoryService
{

	public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Category::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('categories.*')
        ])->where('categories.deleted_at', NULL);
    }

    public static function find($id)
    {
        return Category::find($id);
    }

    public static function create($payload)
    {
        $model = Category::create($payload);
        return $model;
    }

    public static function update($id, $payload)
    {
        $model = Category::find($id);
        return $model->update($payload);
    }

    public static function destroy($id)
    {
        $model = Category::find($id);
        return $model->destroy($id);
    }

    public static function pluck()
    {
        return Category::where('is_active', 1)->pluck('name', 'id');
    }

}