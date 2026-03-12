<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Helpers\CustomImage;

use App\Models\Review;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewService  
{
    public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Review::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('reviews.*')
        ]);

    }

    public static function active()
    {
        return Review::where('is_active', 1)->paginate(5);
    }
   
    public static function find($id)
    {
        return Review::find($id);
    }

    public static function create($payload)
    {
       
        try {
            DB::beginTransaction();

            if (!empty($payload['avatar'])) {
                $upload = CustomImage::storeImage($payload['avatar'], 'reviews');
                $payload['avatar'] = $upload['name'];
            }
            $dataPackage = Arr::except($payload, ['name_id', 'description_id', 'interary_id', 'inclusion_id', 'additional_id']);
            $result = Review::create($dataPackage);
  
            DB::commit();

            return $result;
        } catch (\Throwable $th) {
            BotHelper::errorBot('Create Review', $th);
            DB::rollback();
            return $th;
        }
    }

    public static function update($id, $payload)
    {
        DB::beginTransaction();
        try {
            $model = Review::find($id);
        
            if (!empty($payload['avatar'])) {
                if (!empty($model->avatar)) {
                    Storage::delete('reviews/' . $model->avatar);
                };
                $upload = CustomImage::storeImage($payload['avatar'], 'reviews');
                $payload['avatar'] = $upload['name'];
            }

        

            $result = $model->update($payload);


        

            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            BotHelper::errorBot('Update Review', $th);
            DB::rollBack();
            return $th;
        }
    }

    public static function destroy($id)
    {
        $model = Review::find($id);
        return $model->destroy($id);
    }

    public static function pluck()
    {
        return Review::where('is_active', 1)->pluck('name', 'id');
    }
}
