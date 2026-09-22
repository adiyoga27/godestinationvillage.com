<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PageHero extends Model
{
    use LogsActivity;

    public $table = 'page_heroes';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $logName = 'page_heroes';

    protected static $logOnlyDirty = true;

    public $fillable = [
        'key',
        'name',
        'title',
        'title_id',
        'subtitle',
        'subtitle_id',
        'image',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['key', 'title', 'title_id', 'subtitle', 'subtitle_id', 'image']);
    }
}
