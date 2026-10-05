<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Faq extends Model
{
    use LogsActivity;

    public $table = 'faqs';

    protected static $logName = 'faqs';

    public $fillable = [
        'faq_category_id',
        'question',
        'question_id',
        'answer',
        'answer_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    /** Pertanyaan sesuai bahasa aktif (ID kosong → English). */
    public function localQuestion(): string
    {
        return App::getLocale() === 'id' && filled($this->question_id) ? $this->question_id : $this->question;
    }

    /** Jawaban (HTML) sesuai bahasa aktif (ID kosong → English). */
    public function localAnswer(): string
    {
        return App::getLocale() === 'id' && filled(strip_tags((string) $this->answer_id)) ? $this->answer_id : $this->answer;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['faq_category_id', 'question', 'question_id', 'answer', 'answer_id', 'sort_order', 'is_active'])->logOnlyDirty();
    }
}
