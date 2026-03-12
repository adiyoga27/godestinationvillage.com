<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CategoryHomestay extends Model
{
    use HasFactory;
    use LogsActivity;
    public $table = "category_homestay";
    public $primaryKey = "id";
    public $timestamps = false;
    protected static $logFillable = true;
    protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
    protected static $logName = 'category_homestay';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'id',
       'name',
       'description',
       'is_active'
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'id',
        'name',
        'description','is_active']);
       
    }
}
