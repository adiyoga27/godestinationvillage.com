<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Helpers\CustomImage;
use App\Models\Event;
use App\Models\EventTranslations;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventService  
{
    public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Event::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('events.*')
        ]);

    }

    public static function active()
    {
        return Event::where('is_active', 1)->paginate(5);
    }
    public static function recent()
    {
        return Event::with(['category', 'translate'])->where('is_active', 1)->paginate(5);
    }
    public static function find($id)
    {
        return Event::find($id);
    }

    public static function create($payload)
    {
       
        try {
            DB::beginTransaction();
            if (Auth::user()->role_id == 2) {
                $name = Auth::user()->name;
                BotHelper::sendTelegram("Godevi - Pengajuan Event, \n\nHi, $name \nTelah mengajukan Event dengan judul $payload[name]. Silahkan check akun admin anda untuk melakukan validasi pengajuan event");
                $payload['village_id'] = Auth::user()->village_id;
                $payload['is_paywish'] = false;
                $payload['is_free'] = false;
                $payload['is_active'] = false;
            }
            $payload['slug'] = Str::slug( $payload['name']);
            if (!empty($payload['default_img'])) {
                $upload = CustomImage::storeImage($payload['default_img'], 'events');
                $payload['default_img'] = $upload['name'];
            }
            $dataPackage = Arr::except($payload, ['name_id', 'description_id', 'interary_id', 'inclusion_id', 'additional_id']);
            $model = Event::create($dataPackage);
            $dataTranslate = array(
                'events_id' => $model['id'],
                    'lang' => 'id',
                    'name' => $payload['name_id'],
                    'description' => $payload['description_id'],
                    'interary' => $payload['interary_id'],
                    'inclusion' => $payload['inclusion_id'],
                    'additional' => $payload['additional_id'],  
            );

            $result = EventTranslations::create($dataTranslate);
            DB::commit();
            BotHelper::sendTelegram('Create Event', "Hi, Telah menambahkan Event dengan judul $payload[name]. Silahkan check akun admin anda untuk melakukan validasi pengajuan event");

            return $result;
        } catch (\Throwable $th) {
            BotHelper::errorBot('Create Event', $th);
            file_put_contents('debug_event_service_error.txt', $th->getMessage() . PHP_EOL . $th->getTraceAsString());
            DB::rollback();
            return $th;
        }
    }

    public static function update($id, $payload)
    {
        DB::beginTransaction();
        try {
            $model = Event::find($id);
            $payload['slug'] = Str::slug( $payload['name']);

            if (!empty($payload['default_img'])) {
                if (!empty($model->default_img)) {
                    Storage::delete('events/' . $model->default_img);
                };
                $upload = CustomImage::storeImage($payload['default_img'], 'events');
                $payload['default_img'] = $upload['name'];
            }

        
            $dataPackage = Arr::except($payload, ['name_id', 'description_id', 'interary_id', 'inclusion_id', 'additional_id']);

            $result = $model->update($dataPackage);


            $packageTransData = EventTranslations::where('events_id', $id)
                ->where('lang', 'id');
            if ($packageTransData->count() > 0) {
                //Kalau ISI di Update
                $dataTranslate = array(
                    'name' => $payload['name_id'],
                    'description' => $payload['description_id'],
                    'interary' => $payload['interary_id'],
                    'inclusion' => $payload['inclusion_id'],
                    'additional' => $payload['additional_id'],
                    
                );

                $result = $packageTransData->update($dataTranslate);
            } else {

                //Kalau Kosong Di Insert
                $dataTranslate = array(
                    'events_id' => $id,
                    'lang' => 'id',
                    'name' => $payload['name_id'],
                    'description' => $payload['description_id'],
                    'interary' => $payload['interary_id'],
                    'inclusion' => $payload['inclusion_id'],
                    'additional' => $payload['additional_id'],
                  
                );

                $result = EventTranslations::create($dataTranslate);

            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            BotHelper::errorBot('Update Event', $th);
            DB::rollBack();
            return $th;
        }
    }

    public static function destroy($id)
    {
        $model = Event::find($id);
        return $model->destroy($id);
    }

    public static function pluck()
    {
        return Event::where('is_active', 1)->pluck('name', 'id');
    }
}
