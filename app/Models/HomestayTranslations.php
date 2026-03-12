<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HomestayTranslations extends Model
{
    use HasFactory;
    use LogsActivity;
    public $table = "homestay_translations";
    public $primaryKey = "id";
    public $timestamps = false;
    protected static $logFillable = true;
    protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
    protected static $logName = 'homestay_translations';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'id', 'homestay_id', 'lang', 'name', 'description', 'location', 'facilities', 'additional_activities', 'additional_notes', 
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'id', 'homestay_id', 'lang', 'name', 'description', 'location', 'facilities', 'additional_activities', 'additional_notes', ]);
    }

    public function category()
    {
        return $this->belongsTo(CategoryHomestay::class);
    }
}
