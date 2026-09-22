<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class HomepageSection extends Model
{
    use LogsActivity;

    public $table = 'homepage_sections';

    public $primaryKey = 'id';

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $logName = 'homepage_sections';

    protected static $logOnlyDirty = true;

    public $fillable = [
        'key',
        'name',
        'eyebrow',
        'eyebrow_id',
        'title',
        'title_id',
        'subtitle',
        'subtitle_id',
        'image',
        'button_label',
        'button_label_id',
        'button_url',
        'button2_label',
        'button2_label_id',
        'button2_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['key', 'eyebrow', 'eyebrow_id', 'title', 'title_id', 'subtitle', 'subtitle_id', 'image', 'button_label', 'button_url', 'is_active', 'sort_order']);
    }
}
