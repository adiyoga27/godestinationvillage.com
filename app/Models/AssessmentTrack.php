<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class AssessmentTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'tagline',
        'tagline_en',
        'description',
        'description_en',
        'target_audience',
        'target_audience_en',
        'estimated_minutes',
        'price',
        'scale_labels',
        'is_active',
        'sort_order',
    ];

    /** @var array<string, string>|null cache label dimensi English per request */
    protected ?array $dimensionLabels = null;

    protected $casts = [
        'is_active' => 'boolean',
        'scale_labels' => 'array',
    ];

    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class, 'track_id')->orderBy('sort_order');
    }

    public function activeQuestions()
    {
        return $this->hasMany(AssessmentQuestion::class, 'track_id')->where('is_active', true)->orderBy('sort_order');
    }

    public function results()
    {
        return $this->hasMany(AssessmentResult::class, 'track_id');
    }

    /** Kolom teks sesuai bahasa aktif: versi *_en saat English (kosong → versi Indonesia). */
    public function local(string $field): ?string
    {
        $en = $this->{$field.'_en'} ?? null;

        return App::getLocale() === 'en' && filled($en) ? $en : $this->{$field};
    }

    /**
     * Nama dimensi (kunci Bahasa Indonesia yang tersimpan di hasil) → label bahasa aktif,
     * diambil dari dimension_en soal jalur ini.
     */
    public function dimensionLabel(string $dimension): string
    {
        if (App::getLocale() !== 'en') {
            return $dimension;
        }

        $this->dimensionLabels ??= $this->questions()->whereNotNull('dimension_en')->where('dimension_en', '!=', '')
            ->pluck('dimension_en', 'dimension')->all();

        return $this->dimensionLabels[$dimension] ?? $dimension;
    }
}
