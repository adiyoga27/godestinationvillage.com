<?php

namespace App\Services;

use App\Models\CategoryEvent;
use Illuminate\Support\Facades\DB;

class CategoryEventService  
{
    public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return CategoryEvent::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('category_events.*')
        ]);

    }

    public static function find($id)
    {
        return CategoryEvent::find($id);
    }

    public static function create($payload)
    {
        $model = CategoryEvent::create($payload);
        return $model;
    }

    public static function update($id, $payload)
    {
        $model = CategoryEvent::find($id);
        return $model->update($payload);
    }

    public static function destroy($id)
    {
        $model = CategoryEvent::find($id);
        return $model->destroy($id);
    }

    public static function pluck()
    {
        return CategoryEvent::where('is_active', 1)->pluck('name', 'id');
    }
}
