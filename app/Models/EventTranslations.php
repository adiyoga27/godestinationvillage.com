<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EventTranslations extends Model
{
    use HasFactory;
    use LogsActivity;
    public $table = "event_translations";
    public $primaryKey = "id";
    public $timestamps = false;
    protected static $logFillable = true;
    protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
    protected static $logName = 'event_translations';
    protected static $logOnlyDirty = true;
    public $fillable = [
        'events_id', 'lang', 'name', 'description', 'interary', 'inclusion', 'additional',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['events_id', 'lang', 'name', 'description', 'interary', 'inclusion', 'additional',]);
    }

    public function category()
    {
        return $this->belongsTo(CategoryEvent::class);
    }
}
