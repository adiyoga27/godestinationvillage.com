<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/** Halaman legal yang isinya dikelola admin (key: 'terms'). */
class LegalPage extends Model
{
    use LogsActivity;

    public $table = 'legal_pages';

    protected static $logName = 'legal_pages';

    public $fillable = [
        'key',
        'title',
        'title_id',
        'content',
        'content_id',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'date',
    ];

    public function localTitle(): string
    {
        return App::getLocale() === 'id' && filled($this->title_id) ? $this->title_id : $this->title;
    }

    /** Isi (HTML) sesuai bahasa aktif (ID kosong → English). */
    public function localContent(): string
    {
        return (string) (App::getLocale() === 'id' && filled(strip_tags((string) $this->content_id)) ? $this->content_id : $this->content);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title', 'title_id', 'content', 'content_id', 'last_updated'])->logOnlyDirty();
    }
}
