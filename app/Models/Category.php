<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

use App\Models\Package;
use Spatie\Activitylog\LogOptions;

class Category extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $table = "categories";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'Categories';

    protected static $logOnlyDirty = true;

    public $fillable = [
		'id',
        'name',
        'desc',
        'is_active'
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'id',
        'name',
        'desc',
        'is_active']);
        // Chain fluent methods for configuration options
    }

}
