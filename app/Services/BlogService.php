<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Models\Blog;
use App\Helpers\CustomImage;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class BlogService
{

	public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Blog::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('post.*')
        ]);
    }

    public static function find($id)
    {
        return Blog::find($id);
    }

    public static function create($payload)
    {   
        try {
            DB::beginTransaction();
            $payload['slug'] = Str::slug( $payload['post_title']);

            if (!empty($payload['post_thumbnail'])){
                $upload = CustomImage::storeImage($payload['post_thumbnail'], 'blogs');
                $payload['post_thumbnail'] = $upload['name'];
                $payload['updated_by'] = Auth::user()->id;
    
            }
    
           
            $model = Blog::create(array_merge($payload, ['post_author'=>Auth::user()->id]));
            DB::commit();
            return $model;
        } catch (\Throwable $th) {
            DB::rollback();
            BotHelper::errorBot('Create Blog', $th);

            return $th;
        }
       
    }

    public static function update($id, $payload)
    {
        try {
            DB::beginTransaction();

            $model = Blog::find($id);
            $payload['slug'] = Str::slug( $payload['post_title']);
    
            if (!empty($payload['post_thumbnail'])){
                if (!empty($model->post_thumbnail)){
                    Storage::delete('blogs/'.$model->post_thumbnail);
            };
                $upload = CustomImage::storeImage($payload['post_thumbnail'], 'blogs');
                $payload['post_thumbnail'] = $upload['name'];
                $payload['updated_by'] = Auth::user()->id;
    
            }
    
            $model = Blog::find($id);
            $result = $model->update(array_merge($payload, ['updated_by'=> Auth::user()->id]));
            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            BotHelper::errorBot('Update BLog', $th);
            DB::rollback();
            return $th;
        }
        
       
    }

    public static function destroy($id)
    {
        $model = Blog::find($id);
        if (!empty($model->post_thumbnail)){
            Storage::delete('blogs/'.$model->post_thumbnail);
        };

        return $model->destroy($id);
    }

}