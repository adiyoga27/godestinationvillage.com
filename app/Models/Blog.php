<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\User;
use Spatie\Activitylog\LogOptions;

class Blog extends Model
{
    // use SoftDeletes;
    use LogsActivity;
    public $table = "post";
    protected $dates = ['last_update'];
    protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
    public $primaryKey = "id";
    public $timestamps = true;
    protected static $logFillable = true;
    protected static $logName = 'post';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'id',
        'post_title',
        'post_content',
        'post_thumbnail',
        'post_author',
        'isPublished',
        'liked_by',
        'updated_by', 'slug','village_id'
    ];

    //buat cast

    protected  $casts = [
        'liked_by' => 'array',
    ];

 

    public function user()
    {
        return $this->belongsTo(User::class, 'post_author', 'id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([ 'id',
        'post_title',
        'post_content',
        'post_thumbnail',
        'post_author',
        'isPublished',
        'updated_by', 'slug','village_id']);
        // Chain fluent methods for configuration options
    }

    
}
