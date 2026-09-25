<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'track_id',
        'name',
        'email',
        'phone',
        'organization',
        'answers',
        'dimension_scores',
        'total_score',
        'band',
        'status',
        'pic_team_id',
        'internal_note',
    ];

    protected $casts = [
        'answers' => 'array',
        'dimension_scores' => 'array',
        'total_score' => 'float',
    ];

    public function track()
    {
        return $this->belongsTo(AssessmentTrack::class, 'track_id');
    }

    public function pic()
    {
        return $this->belongsTo(OurTeam::class, 'pic_team_id');
    }
}
