<?php

namespace App\Services;

use App\Models\Instragram;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
class InstagramServices
{

	public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Instragram::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('instagram.*')
        ]);
    }

    public static function find($id)
    {
        return Instragram::find($id);
    }

    public static function randomPost()
    {
        return Instragram::all()->shuffle()->first()->url;
    }
    public static function create($payload)
    {
        $model = Instragram::create($payload);
        return $model;
    }

    public static function update($id, $payload)
    {
        $model = Instragram::find($id);
        return $model->update($payload);
    }

    public static function destroy($id)
    {
        $model = Instragram::find($id);
        return $model->destroy($id);
    }

    public static function pluck()
    {
        return Instragram::where('is_active', 1)->pluck('name', 'id');
    }

}