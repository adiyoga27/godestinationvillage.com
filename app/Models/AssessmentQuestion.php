<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AssessmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
        'dimension',
        'dimension_en',
        'question',
        'question_en',
        'help_text',
        'help_text_en',
        'weight',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function track()
    {
        return $this->belongsTo(AssessmentTrack::class, 'track_id');
    }

    /** Kolom teks sesuai bahasa aktif: versi *_en saat English (kosong → versi Indonesia). */
    public function local(string $field): ?string
    {
        $en = $this->{$field.'_en'} ?? null;

        return App::getLocale() === 'en' && filled($en) ? $en : $this->{$field};
    }
}
