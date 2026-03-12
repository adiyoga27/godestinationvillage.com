<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class PackageTranslations extends Model
{
    use LogsActivity;

    public $table = "package_translations";


    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];

    protected static $logName = 'package_translations';

    protected static $logOnlyDirty = true;

    public $fillable = [
        'id', 'package_id', 'lang', 'name', 'desc', 'review', 'itenaries', 'inclusion', 'exclusion', 'term', 'duration', 'preparation'
    ];


    public function details()
    {
        return $this->hasOne(Package::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function village()
    {
        return $this->belongsTo(VillageDetail::class, 'category_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function translate()
    {
        return $this->hasMany(PackageTranslations::class, 'package_id', 'id');
    }
    public function tag()
    {
        return $this->hasOne(CategoryPackage::class, 'package_id', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'id', 'package_id', 'lang', 'name', 'desc', 'review', 'itenaries', 'inclusion', 'exclusion', 'term', 'duration', 'preparation']);
        // Chain fluent methods for configuration options
    }
}
