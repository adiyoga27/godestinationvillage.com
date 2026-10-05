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
        DB::statement('set @rownum=0');
        return Homestay::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('homestay.*')
        ])->where('homestay.deleted_at', NULL);

    }

    public static function active()
    {
        return Homestay::with('translate')->where('is_active', 1)->paginate(5);
    }
    /** Pilihan urutan di halaman /homestay. */
    public const SORTS = ['recommended', 'price_asc', 'price_desc', 'newest'];

    /** Rentang harga per malam (harga akhir setelah diskon): key => [min, max]. */
    public const PRICE_RANGES = [
        'under-300k' => [0, 299999],
        '300k-700k' => [300000, 700000],
        '700k-1500k' => [700001, 1500000],
        'over-1500k' => [1500001, null],
    ];

    /**
     * Daftar homestay aktif dengan filter halaman /homestay.
     *
     * @param  array{q?: string, village?: int|string, type?: int|string, price?: string, breakfast?: bool, sort?: string}  $filters
     */
    public static function search(array $filters, int $perPage = 9)
    {
        $finalPrice = 'CASE WHEN homestay.disc > 0 THEN homestay.disc ELSE homestay.price END';

        $query = Homestay::with(['translate', 'category', 'village'])
            ->select('homestay.*')
            ->where('homestay.is_active', 1);

        if (filled($filters['q'] ?? null)) {
            $term = '%'.str_replace(['%', '_'], ['\\%', '\\_'], trim($filters['q'])).'%';
            $query->where(function ($q) use ($term) {
                $q->where('homestay.name', 'like', $term)
                    ->orWhere('homestay.location', 'like', $term)
                    ->orWhere('homestay.owner_name', 'like', $term)
                    ->orWhereHas('village', fn ($v) => $v->where('village_name', 'like', $term))
                    ->orWhereHas('translate', fn ($t) => $t->where('name', 'like', $term)->orWhere('location', 'like', $term));
            });
        }
        if (filled($filters['village'] ?? null)) {
            $query->where('homestay.village_id', (int) $filters['village']);
        }
        if (filled($filters['type'] ?? null)) {
            $query->where('homestay.category_id', (int) $filters['type']);
        }
        if ($range = self::PRICE_RANGES[$filters['price'] ?? ''] ?? null) {
            $query->whereRaw("$finalPrice >= ?", [$range[0]]);
            if ($range[1] !== null) {
                $query->whereRaw("$finalPrice <= ?", [$range[1]]);
            }
        }
        if (! empty($filters['breakfast'])) {
            $query->where('homestay.is_breakfast', 1);
        }

        match ($filters['sort'] ?? 'recommended') {
            'price_asc' => $query->orderByRaw("$finalPrice asc"),
            'price_desc' => $query->orderByRaw("$finalPrice desc"),
            'newest' => $query->orderByDesc('homestay.created_at'),
            default => $query->orderByDesc('homestay.disc')->orderBy('homestay.id'),
        };

        return $query->orderBy('homestay.id')->paginate($perPage)->withQueryString();
    }

    /** Pilihan filter: hanya desa & tipe kamar yang punya homestay aktif. */
    public static function filterOptions(): array
    {
        $active = Homestay::where('is_active', 1);

        return [
            'villages' => \App\Models\VillageDetail::whereIn('id', (clone $active)->select('village_id'))
                ->orderBy('village_name')->pluck('village_name', 'id'),
            'types' => \App\Models\CategoryHomestay::whereIn('id', (clone $active)->select('category_id'))
                ->orderBy('id')->pluck('name', 'id'),
        ];
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

    /** Soft delete: baris tetap ada (deleted_at terisi) agar riwayat order tidak rusak. */
    public static function destroy($id): bool
    {
        return (bool) Homestay::findOrFail($id)->delete();
    }

    public static function pluck()
    {
        return Homestay::where('is_active', 1)->pluck('name', 'id');
    }
}
