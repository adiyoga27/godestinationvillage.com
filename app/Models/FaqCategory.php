<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FaqCategory extends Model
{
    use LogsActivity;

    public $table = 'faq_categories';

    protected static $logName = 'faq_categories';

    public $fillable = [
        'title',
        'title_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order')->orderBy('id');
    }

    /** Judul sesuai bahasa aktif (ID kosong → English). */
    public function localTitle(): string
    {
        return App::getLocale() === 'id' && filled($this->title_id) ? $this->title_id : $this->title;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title', 'title_id', 'sort_order', 'is_active'])->logOnlyDirty();
    }
}
