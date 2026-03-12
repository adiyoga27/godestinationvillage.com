<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Models\User;
use App\Helpers\CustomImage;
use App\Http\Resources\Slider\SliderResource;
use App\Models\Slider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserService
{

	public static function find($id)
    {
        return User::find($id);
    }

    public static function count_by_role($id)
    {
        return User::where('role_id', $id)->count();
    }

    public static function update_profile($id, $payload)
    {
        $model = User::find($id);

        if(!empty($payload['password'])){
            $payload['password'] = bcrypt($payload['password']);
        };

        if (!empty($payload['avatar'])){
            if (!empty($model->avatar)){
                Storage::delete('users/'.$model->avatar);
            };
            $upload = CustomImage::storeImage($payload['avatar'], 'users');
            $payload['avatar'] = $upload['name'];
        }

        return $model->update($payload);
    }

    public static function get_by_role($role_id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        return User::query()
            ->leftjoin('village_details', 'users.village_id' , '=' , 'village_details.id')
            ->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('users.*'),
            DB::raw('village_details.village_name')

        ])->where('role_id', $role_id);
    }

    public static function create($payload)
    {
     
        if(!empty($payload['password'])){
            $payload['password'] = bcrypt($payload['password']);
        };

        if (!empty($payload['avatar'])){
            $upload = CustomImage::storeImage($payload['avatar'], 'users');
            $payload['avatar'] = $upload['name'];
        }

        $model = User::create($payload);
        $role = DB::table('model_has_roles')
                    ->insert([
                        'role_id' => $payload['role_id'],
                        'model_type' => 'App\Models\User',
                        'model_id' => $model->id
                    ]);
        return $model;
    }

    public static function update($id, $payload)
    {
        $model = User::find($id);

        if(!empty($payload['password'])){
            $payload['password'] = bcrypt($payload['password']);
        };

        if (!empty($payload['avatar'])){
            if (!empty($model->avatar)){
                Storage::delete('users/'.$model->avatar);
            };
            $upload = CustomImage::storeImage($payload['avatar'], 'users');
            $payload['avatar'] = $upload['name'];
        }

        $role = DB::table('model_has_roles')->where('model_id', $id)
                    ->update([
                        'role_id' => $payload['role_id'],
                        'model_type' => 'App\Models\User'
                    ]);

        return $model->update($payload);
    }

    public static function destroy($id)
    {
        $model = User::find($id);

        if (!empty($payload['avatar'])){
            if (!empty($model->avatar)){
                Storage::delete('users/'.$model->avatar);
            };
        }

        return $model->destroy($id);
    }

    static function registration($request)
    {
        try {
            return User::create([
                'name' => $request->name,
                'email' =>  $request->email,
                'password' => Hash::make( $request->password),
                'country' =>  $request->country,
                'role_id' =>  3,
                'phone' =>  $request->phone,
                'address' =>  $request->address,
                'avatar' => $request->avatar
            ]);

        } catch (\Throwable $th) {
            BotHelper::errorBot('Registration User', $th);

           return FALSE;
        }
     

    }

    static function getSliders()
    {
        try {
            return new SliderResource(Slider::all());
            
        } catch (\Throwable $th) {
            BotHelper::errorBot('Error Get Slider', $th);

            return $th;
        }
    }
}