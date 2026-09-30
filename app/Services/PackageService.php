<?php

namespace App\Services;

use App\Helpers\BotHelper;
use App\Models\Package;
use Illuminate\Support\Arr;
use App\Helpers\CustomImage;
use App\Models\PackageTranslations;
use App\Models\User;
use App\Models\VillageDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PackageService
{

    public static function find($id)
    {
        return Package::with('translate')->find($id);
    }

    public static function count($user_id = NULL)
    {
        if ($user_id == NULL)
            return Package::count();
        else
            return Package::where('user_id', $user_id)->count();
    }

    public static function find_with($id)
    {
        return Package::with(['category', 'orders', 'user.village_detail', 'detailVillage'])->find($id);
    }

    public static function find_by_user($user_id)
    {
        DB::statement('set @rownum=0');
        return Package::query()
            ->join('categories', 'packages.category_id', '=', 'categories.id')
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                DB::raw('packages.*'),
                DB::raw('categories.name AS category_name')
            ])->where('packages.user_id', $user_id)->where('packages.deleted_at', NULL);
    }

    public static function find_by_village($user_id)
    {
        return Package::where('user_id', $user_id)->get();
    }

    public static function all()
    {
        DB::statement('set @rownum=0');
        return Package::query()
            ->join('categories', 'packages.category_id', '=', 'categories.id')
            ->leftjoin('village_details', 'packages.village_id', '=', 'village_details.id')
            ->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                DB::raw('packages.*'),
                DB::raw('categories.name AS category_name'),
                DB::raw('village_details.village_name AS village_name')
            ])->where('packages.deleted_at', NULL);
    }

    /**
     * Resolve desa wisata -> (village_id, user_id pemilik desa).
     * $villageDetailId = village_details.id (dari dropdown admin).
     * Return [village_id, user_id] atau [null, null] jika tidak ketemu.
     */
    protected static function resolveVillageIds($villageDetailId)
    {
        if (empty($villageDetailId)) {
            return [null, null];
        }
        $detail = VillageDetail::find($villageDetailId);
        if ($detail) {
            return [$detail->id, $detail->user_id];
        }
        return [null, null];
    }

    public static function create($payload)
    {
        try {
            DB::beginTransaction();
            $payload['slug'] = Str::slug( $payload['name']);
            if (Auth::user()->role_id == 2) {
                $payload['user_id'] = Auth::user()->id;
                $payload['village_id'] = Auth::user()->village_id;
                $payload['is_active'] = false;
                $name = Auth::user()->name;
                BotHelper::sendTelegram("Godevi - Pengajuan Tour Package, \n\nHi, $name \nTelah mengajukan Paket Wisata dengan judul $payload[name]. Silahkan check akun admin anda untuk melakukan validasi pengajuan paket wisata");

            }else{
                // Form admin mengirim village_id (village_details.id).
                // Dukung juga payload lama yang mengirim user_id berisi village_details.id.
                $villageDetailId = $payload['village_id'] ?? $payload['user_id'] ?? null;
                [$villageId, $ownerUserId] = self::resolveVillageIds($villageDetailId);
                if ($villageId) {
                    $payload['village_id'] = $villageId;
                    $payload['user_id'] = $ownerUserId;
                } else {
                    // Fallback: user_id mungkin users.id asli (bukan village_details.id).
                    $owner = !empty($payload['user_id']) ? User::find($payload['user_id']) : null;
                    if ($owner && $owner->village_id) {
                        $payload['village_id'] = $owner->village_id;
                        $payload['user_id'] = $owner->id;
                    } else {
                        $payload['village_id'] = $villageDetailId;
                    }
                }

            }
            if (!empty($payload['default_img'])) {
                $upload = CustomImage::storeImage($payload['default_img'], 'packages');
                $payload['default_img'] = $upload['name'];
            }
            $dataPackage = Arr::except($payload, ['name_id', 'desc_id', 'itenaries_id', 'inclusion_id', 'term_id', 'duration_id', 'preparation_id', 'tag_id','review','other_img']);

            $model = Package::create($dataPackage);

            if (!empty($payload['other_img'])) {
                foreach ($payload['other_img'] as $value) {
                    $upload_other = CustomImage::storeImage($value, 'packages/' . $model->id);
                }
            }
            $dataTranslate = array(
                'package_id' => $model['id'],
                'lang' => 'id',
                'name' => $payload['name_id'],
                'desc' => $payload['desc_id'],
                'itenaries' => $payload['itenaries_id'],
                'inclusion' => $payload['inclusion_id'],
                // 'exclusion' => $payload['exclusion_id'],
                'term' => $payload['term_id'],
                'duration' => $payload['duration_id'],
                'preparation' => $payload['preparation_id'],
              

                
            );

            $result = PackageTranslations::create($dataTranslate);
            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollback();
            BotHelper::errorBot('Create Tour Package', $th);

            return $th;
        }
    }



    public static function update($id, $payload)
    {
        try {
            $model = Package::find($id);
            if (!$model) {
                return false;
            }
            $payload['slug'] = Str::slug( $payload['name']);

            if (!empty($payload['default_img'])) {
                if (!empty($model->default_img)) {
                    Storage::delete('packages/' . $model->default_img);
                };
                $upload = CustomImage::storeImage($payload['default_img'], 'packages');
                $payload['default_img'] = $upload['name'];
            }

            if (!empty($payload['other_img'])) {
                foreach ($payload['other_img'] as $value) {
                    $upload_other = CustomImage::storeImage($value, 'packages/' . $model->id);
                }
            }

            // Jangan biarkan user desa mengganti milik desa lain.
            if (Auth::check() && Auth::user()->role_id == 2) {
                $payload['user_id'] = Auth::user()->id;
                $payload['village_id'] = Auth::user()->village_id;
            } elseif (isset($payload['village_id']) || isset($payload['user_id'])) {
                // Admin: village_id adalah village_details.id.
                // Edit manual dari admin otomatis meluruskan user_id yang salah (data lama).
                // Dukung juga payload lama: user_id berisi village_details.id.
                $villageDetailId = $payload['village_id'] ?? $payload['user_id'] ?? null;
                [$villageId, $ownerUserId] = self::resolveVillageIds($villageDetailId);
                if ($villageId) {
                    $payload['village_id'] = $villageId;
                    $payload['user_id'] = $ownerUserId;
                } elseif (!empty($payload['user_id'])) {
                    $owner = User::find($payload['user_id']);
                    if ($owner && $owner->village_id) {
                        $payload['village_id'] = $owner->village_id;
                        $payload['user_id'] = $owner->id;
                    }
                }
            }

            $dataPackage = Arr::except($payload, ['name_id', 'desc_id', 'itenaries_id', 'inclusion_id', 'term_id', 'duration_id', 'preparation_id','review','other_img']);

            if(empty($dataPackage['village_id'] ?? $model->village_id)){

                $ownerUser = User::where('id', $dataPackage['user_id'] ?? $model->user_id)->first();
                if ($ownerUser && $ownerUser->village_id) {
                    $dataPackage['village_id'] = $ownerUser->village_id;
                }
            }
            // Self-healing data lama: jika user_id tidak cocok dengan pemilik village_id, luruskan.
            if (!empty($dataPackage['village_id'] ?? $model->village_id)) {
                $vid = $dataPackage['village_id'] ?? $model->village_id;
                $detail = VillageDetail::find($vid);
                if ($detail && ($dataPackage['user_id'] ?? $model->user_id) != $detail->user_id) {
                    $dataPackage['user_id'] = $detail->user_id;
                }
            }
            $model->update($dataPackage);


            $packageTransData = PackageTranslations::where('package_id', $id)
                ->where('lang', 'id');
            if ($packageTransData->count() > 0) {
                //Kalau ISI di Update
                $dataTranslate = array(
                    'name' => $payload['name_id'],
                    'desc' => $payload['desc_id'],
                    'itenaries' => $payload['itenaries_id'],
                    'inclusion' => $payload['inclusion_id'],
                    // 'exclusion' => $payload['exclusion_id'],
                    'term' => $payload['term_id'],
                    'duration' => $payload['duration_id'],
                    'preparation' => $payload['preparation_id']
                );

                $result = $packageTransData->update($dataTranslate);
            } else {
                //Kalau Kosong Di Insert
                $dataTranslate = array(
                    'package_id' => $id,
                    'lang' => 'id',
                    'name' => $payload['name_id'],
                    'desc' => $payload['desc_id'],
                    'itenaries' => $payload['itenaries_id'],
                    'inclusion' => $payload['inclusion_id'],
                    // 'exclusion' => $payload['exclusion_id'],
                    'term' => $payload['term_id'],
                    'duration' => $payload['duration_id'],
                    'preparation' => $payload['preparation_id']
                );

                $result = PackageTranslations::create($dataTranslate);
            }
            return $result;
        } catch (\Throwable $th) {
            BotHelper::errorBot('Update Tour Package', $th);

            return false;
        }
    }


    public static function destroy($id)
    {
        $model = Package::find($id);

        // if (!empty($payload['default_img'])) {
        //     if (!empty($model->default_img)) {
        //         Storage::delete('packages/' . $model->default_img);
        //     };
        // }

        // Storage::deleteDirectory('packages/' . $model->id);

        return $model->destroy($id);
    }

    public static function pluck($user_id)
    {
        return Package::where('user_id', $user_id)->pluck('name', 'id');
    }
}
