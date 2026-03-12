<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Instragram extends Model
{
    // use SoftDeletes;
    use LogsActivity;
    public $table = "instagram";
    public $primaryKey = "id";
    public $timestamps = false;
    protected static $logFillable = true;
    protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
    protected static $logName = 'instagram';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'id',
       'name',
       'url',
       'is_active'

    ];

    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([   'id',
        'name',
        'url',
        'is_active']);
        // Chain fluent methods for configuration options
    }
}
