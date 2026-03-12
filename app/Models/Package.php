<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

use App\Models\User;
use App\Models\Category;
use App\Models\Order;
use App\Models\Tag;
use Spatie\Activitylog\LogOptions;

class Package extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $table = "packages";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at', 'updated_at', 'deleted_at'];

    protected static $logName = 'Packages';

    protected static $logOnlyDirty = true;

    public $fillable = [
        'id',
        'category_id',
        'user_id',
        'tag_id',
        'name',
        'desc',
        'review',
        'itenaries',
        'inclusion',
        'exclusion',
        'term',
        'duration', 'preparation',
        'price',
        'disc',
        'is_active',
        'default_img',
        'slug',
        'village_id'

    ];

    protected $casts = [
        'review' => 'decimal:1',
    ];

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
    public function villageDetail()
    {
        return $this->belongsTo(VillageDetail::class, 'village_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function detailVillage()
    {
        return $this->hasOne(VillageDetail::class, 'id', 'village_id');
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
        ->logOnly(['id',
        'category_id',
        'user_id',
        'tag_id',
        'name',
        'desc',
        'review',
        'itenaries',
        'inclusion',
        'exclusion',
        'term',
        'duration', 'preparation',
        'price',
        'price_disc',
        'is_active',
        'default_img',
        'village_id'
    ]);
        // Chain fluent methods for configuration options
    }
}
