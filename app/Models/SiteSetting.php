<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SiteSetting extends Model
{
    use LogsActivity;

    public $table = 'site_settings';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $logName = 'site_settings';

    protected static $logOnlyDirty = true;

    public $fillable = [
        'key',
        'label',
        'value',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['key', 'value']);
    }
}
