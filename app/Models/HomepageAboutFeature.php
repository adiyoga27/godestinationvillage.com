<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HomepageAboutFeature extends Model
{
    use LogsActivity;

    public $table = 'homepage_about_features';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $logName = 'homepage_about_features';

    protected static $logOnlyDirty = true;

    public $fillable = [
        'title',
        'title_id',
        'desc',
        'desc_id',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'title_id', 'desc', 'desc_id', 'image', 'sort_order', 'is_active']);
    }
}
