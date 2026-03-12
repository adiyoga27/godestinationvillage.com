<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Review extends Model
{
    // use SoftDeletes;
    use LogsActivity;
    public $table = "reviews";
    public $primaryKey = "id";
    public $timestamps = false;
    protected static $logFillable = true;
    protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
    protected static $logName = 'post';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'id', 'user_id', 'inv', 'rating', 'job', 'name', 'comment', 'avatar','is_active'
    ];


    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'id', 'user_id', 'inv', 'rating', 'job', 'name', 'comment', 'avatar','is_active']);
        // Chain fluent methods for configuration options
    }
    
    
}
