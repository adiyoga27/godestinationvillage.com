<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Homestay extends Model
{
    use HasFactory;
    use LogsActivity;
    // Hapus dari admin = isi deleted_at; riwayat order tetap merujuk ke homestay ini.
    use SoftDeletes;
    public $table = "homestay";
    protected $dates = ['deleted_at'];

    public $primaryKey = "id";
    public $timestamps = false;
    protected static $logFillable = true;
    protected static $ignoreChangedAttributes = ['update_at', 'created_at','deleted_at'];
    protected static $logName = 'homestay';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'id', 'category_id', 'name', 'description', 'location', 'price', 'disc', 'facilities', 'is_breakfast', 'additional_activities', 'owner_name', 'check_in_time', 'check_out_time', 'additional_notes', 'is_active','default_img', 'slug','village_id'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'id', 'category_id', 'name', 'description', 'location', 'price', 'disc', 'facilities', 'is_breakfast', 'additional_activities', 'owner_name', 'check_in_time', 'check_out_time', 'additional_notes', 'is_active','default_img', 'slug','village_id']);
        // Chain fluent methods for configuration options
    }

    public function category()
    {
        return $this->belongsTo(CategoryHomestay::class);
    }
    public function village()
    {
        return $this->belongsTo(VillageDetail::class, 'village_id');
    }

    /** Harga yang dibayar per malam: kolom disc = harga setelah diskon (0 = tanpa diskon). */
    public function finalPrice(): int
    {
        return (int) ($this->disc > 0 ? $this->disc : $this->price);
    }

    public function translate()
    {
        return $this->hasMany(HomestayTranslations::class, 'homestay_id', 'id');
    }
}
