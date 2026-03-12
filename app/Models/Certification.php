<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Certification extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    public $table = "certification";

    protected $dates = ['deleted_at'];

    public $primaryKey = "id";

    public $timestamps = true;

    protected static $logFillable = true;

    protected static $ignoreChangedAttributes = ['created_at','updated_at','deleted_at'];

    protected static $logName = 'Certification';

    protected static $logOnlyDirty = true;

    public $fillable = [
        'category',  'reference_number', 'date_at', 'regarding', 'signer', 'departemen', 'file', 'isActive','addressed_to','slug'
    ];

      
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'category', 'reference_number', 'date_at', 'regarding', 'signer', 'departemen', 'file', 'isActive','addressed_to','slug']);
        // Chain fluent methods for configuration options
    }
}
