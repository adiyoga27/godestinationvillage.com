<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Slider extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $table = "slider";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'Slider';

    protected static $logOnlyDirty = true;

    public $fillable = [
		'id',
        'title',
        'title_id',
        'desc',
        'desc_id',
        'img',
        'button_label',
        'button_label_id',
        'button_url',
        'button_color',
        'button2_label',
        'button2_label_id',
        'button2_url',
        'button2_color',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id',
        'title',
        'title_id',
        'desc',
        'desc_id',
        'img',
        'button_label',
        'button_label_id',
        'button_url',
        'button_color',
        'button2_label',
        'button2_label_id',
        'button2_url',
        'button2_color',]);
        // Chain fluent methods for configuration options
    }

}
