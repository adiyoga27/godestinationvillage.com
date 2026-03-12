<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Helpers\CustomImage;
use App\Models\Homestay;
use App\Models\HomestayTranslations;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeStayServices  
{
    public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Homestay::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('homestay.*')
        ])->where('homestay.deleted_at', NULL);

    }

    public static function active()
    {
        return Homestay::where('is_active', 1)->paginate(5);
    }
    public static function recent()
    {
        return Homestay::with(['category', 'translate'])->where('is_active', 1)->paginate(5);
    }
    public static function find($id)
    {
        return Homestay::find($id);
    }

    public static function create($payload)
    {
        try {
            DB::beginTransaction();

            if (Auth::user()->role_id == 2) {
                $payload['village_id'] = Auth::user()->village_id;
                $payload['is_active'] = false;
                $name = Auth::user()->name;
                BotHelper::sendTelegram("Godevi - Pengajuan Home Stay, \n\nHi, $name \nTelah mengajukan Homestay dengan judul $payload[name]. Silahkan check akun admin anda untuk melakukan validasi pengajuan homestay");


            }
       

            $payload['slug'] = Str::slug( $payload['name']);

            if (!empty($payload['default_img'])) {
                $upload = CustomImage::storeImage($payload['default_img'], 'homestay');
                $payload['default_img'] = $upload['name'];
            }

            $dataPackage = Arr::except($payload, ['name_id', 'description_id', 'location_id', 'facilities_id', 'additional_activities_id','additional_notes_id']);
           
            $model = Homestay::create($dataPackage);

            $dataTranslate = array(
                'homestay_id' => $model['id'],
                    'lang' => 'id',
                    'name' => $payload['name_id'],
                    'description' => $payload['description_id'],
                    'location' => $payload['location_id'],
                    'facilities' => $payload['facilities_id'],
                    'additional_activities' => $payload['additional_activities_id'],
                    'additional_notes' => $payload['additional_notes_id'],
            );


            $result = HomestayTranslations::create($dataTranslate);
            
            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            BotHelper::errorBot('Create Homestay', $th);
            DB::rollback();
            return $th;
        }
    }

    public static function update($id, $payload)
    {

        DB::beginTransaction();
        try {
            $payload['slug'] = Str::slug( $payload['name']);

            $model = Homestay::find($id);

            if (!empty($payload['default_img'])) {
                if (!empty($model->default_img)) {
                    Storage::delete('homestay/' . $model->default_img);
                };
                $upload = CustomImage::storeImage($payload['default_img'], 'homestay');
                $payload['default_img'] = $upload['name'];
            }

        
            $dataPackage = Arr::except($payload, ['name_id', 'description_id', 'location_id', 'facilities_id', 'additional_activities_id','additional_notes_id']);

            $result =  $model->update($dataPackage);


            $packageTransData = HomestayTranslations::where('homestay_id', $id)
                ->where('lang', 'id');
                
            if ($packageTransData->count() > 0) {
                //Kalau ISI di Update
                $dataTranslate = array(
                  
                    'name' => $payload['name_id'],
                    'description' => $payload['description_id'],
                    'location' => $payload['location_id'],
                    'facilities' => $payload['facilities_id'],
                    'additional_activities' => $payload['additional_activities_id'],
                    'additional_notes' => $payload['additional_notes_id']
                );

                $result = $packageTransData->update($dataTranslate);

            } else {

                //Kalau Kosong Di Insert
                $dataTranslate = array(
                    'homestay_id' => $id,
                    'lang' => 'id',
                    'name' => $payload['name_id'],
                    'description' => $payload['description_id'],
                    'location_id' => $payload['location_id'],
                    'facilities_id' => $payload['facilities_id'],
                    'additional_activities_id' => $payload['additional_activities_id'],
                    'additional_notes_id' => $payload['additional_notes_id']
                  
                );

                $result = HomestayTranslations::create($dataTranslate);

            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            BotHelper::errorBot('Update Homestay', $th);

            return $th;
        }
    }

    public static function destroy($id)
    {
        $model = Homestay::find($id);
        return $model->destroy($id);
    }

    public static function pluck()
    {
        return Homestay::where('is_active', 1)->pluck('name', 'id');
    }
}
