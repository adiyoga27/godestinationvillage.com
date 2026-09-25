<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'target_audience',
        'estimated_minutes',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
}
