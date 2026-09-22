<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HomepageService extends Model
{
    use LogsActivity;

    public $table = 'homepage_services';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $logName = 'homepage_services';

    protected static $logOnlyDirty = true;

    public $fillable = [
        'title',
        'title_id',
        'image',
        'url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'title_id', 'image', 'url', 'sort_order', 'is_active']);
    }
}
