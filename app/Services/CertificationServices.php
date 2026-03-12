<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Helpers\CustomImage;
use App\Models\Certification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class CertificationServices
{

	public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return Certification::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('certification.*')
        ]);
    }

    public static function find($id)
    {
        return Certification::find($id);
    }

    public static function create($payload)
    {   
        try {
            DB::beginTransaction();

            $payload['slug'] =  str_replace(str_split('\\/:*?"<>|.'), '', $payload['reference_number']);
            if (!empty($payload['file'])){

                $upload = CustomImage::storeFile($payload['file'], 'certification');

                $payload['file'] = $upload['name'];
            }
            $imageQr = \QrCode::format('png')
                ->merge('customer/img/qr.png', 0.5, true)
                ->size(500)->errorCorrection('H')
                ->generate(url("surat")."/".$payload['slug']);
            
            $output_file = 'public/certification/qr-code/qr-' . $payload['slug'] . '.png';
            Storage::disk('local')->put($output_file, $imageQr);
            $model = Certification::create($payload);

            DB::commit();
            return $model;
        } catch (\Throwable $th) {
        

            DB::rollback();
            BotHelper::errorBot('Create Certification', $th);

            return $th;
        }
       
    }

    public static function update($id, $payload)
    {
        try {
            DB::beginTransaction();

            $model = Certification::find($id);
            $payload['slug'] =  str_replace(str_split('\\/:*?"<>|.'), '', $payload['reference_number']);
    
            if (!empty($payload['file'])){
                if (!empty($model->file)){
                        Storage::delete('certification/'.$model->file);
                };
                

                $upload = CustomImage::storeFile($payload['file'], 'certification');

                $payload['file'] = $upload['name'];
            }
            Storage::delete('qr-code/qr-'.$model->file);
            $imageQr = \QrCode::format('png')
            ->merge('customer/img/qr.png', 0.5, true)
            ->size(500)->errorCorrection('H')
            ->generate(url("surat")."/".$payload['slug']);
            $output_file = 'public/certification/qr-code/qr-' . $payload['slug'] . '.png';
            Storage::disk('local')->put($output_file, $imageQr);

    
            $model = Certification::find($id);
            $result = $model->update(array_merge($payload));
            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            dd($th);
            BotHelper::errorBot('Update Cerificate', $th);
            DB::rollback();
            return $th;
        }
        
       
    }

    public static function destroy($id)
    {
        $model = Certification::find($id);
        if (!empty($model->post_thumbnail)){
            Storage::delete('blogs/'.$model->post_thumbnail);
        };

        return $model->destroy($id);
    }

}