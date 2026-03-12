<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MemberDiscount extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $table = "member_discounts";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'Member Discounts';

    protected static $logOnlyDirty = true;

    public $fillable = [
		'id',
        'type',
        'value',
        'is_active'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([	'id',
        'type',
        'value',
        'is_active']);
        // Chain fluent methods for configuration options
    }
}
