<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Consulting extends Model
{
        // use SoftDeletes;
        use LogsActivity;
        public $table = "consulting_service";
        public $primaryKey = "id";
        public $timestamps = false;
        protected static $logFillable = true;
        protected static $ignoreChangedAttributes = ['update_at', 'created_at'];
        protected static $logName = 'consulting';
        protected static $logOnlyDirty = true;
        public $fillable = [
            'id',
            'title',
            'desc',
            'subtitle',
        ];
    
        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly([  'id',
        'title',
        'desc',
        'subtitle']);
        // Chain fluent methods for configuration options
    }
    
}
