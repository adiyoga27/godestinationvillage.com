<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CategoryPackage extends Model
{
    // use SoftDeletes;
    use LogsActivity;
    public $table = "category_packages";
    public $primaryKey = "id";
    public $timestamps = false;
    protected static $logFillable = true;
    protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
    protected static $logName = 'category_packages';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'id',
       'package_id',
       'tag_id'
    ];

    
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
    public function package()
    {
        return $this->hasManyThrough(CategoryPackage::class, Package::class, 'id', 'package_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'id',
        'package_id',
        'tag_id']);
        // Chain fluent methods for configuration options
    }
    
}
