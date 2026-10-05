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
       'name_id',
       'desc',
       'desc_id',
       'image',
       'url',
       'sort_order',
       'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /** Judul kartu sesuai bahasa aktif (ID kosong → English). */
    public function localName(): string
    {
        return \Illuminate\Support\Facades\App::getLocale() === 'id' && filled($this->name_id) ? $this->name_id : (string) $this->name;
    }

    /** Deskripsi kartu sesuai bahasa aktif (ID kosong → English). */
    public function localDesc(): string
    {
        return \Illuminate\Support\Facades\App::getLocale() === 'id' && filled($this->desc_id) ? $this->desc_id : (string) $this->desc;
    }

    
    public function detail()
    {
        return $this->belongsTo(CategoryPackage::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['id', 'name', 'name_id', 'desc', 'desc_id', 'image', 'url', 'sort_order', 'status']);
        // Chain fluent methods for configuration options
    }

}
