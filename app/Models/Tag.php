<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tag extends Model
{
    // use SoftDeletes;
    use LogsActivity;
    public $table = "tag_category";
    public $primaryKey = "id";
    public $timestamps = false;
    protected static $logFillable = true;
    protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
    protected static $logName = 'tag';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'id',
       'name',
       'desc',
       'image',
       'status'
    ];

    
    public function detail()
    {
        return $this->belongsTo(CategoryPackage::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id',
        'name',
        'desc',
        'img',]);
        // Chain fluent methods for configuration options
    }

}
