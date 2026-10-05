<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/** Satu teks website (kunci lang/*.json) yang diubah dari admin, per bahasa. */
class TranslationOverride extends Model
{
    use LogsActivity;

    public $table = 'translation_overrides';

    protected static $logName = 'translation_overrides';

    public $fillable = ['locale', 'key_hash', 'key', 'value'];

    public static function hashKey(string $key): string
    {
        return hash('sha256', $key);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['locale', 'key', 'value'])->logOnlyDirty();
    }
}
