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
        'business_type',
        'business_sector',
        'member_count',
        'subdistrict',
        'district',
        'regency',
        'province',
        'profile_description',
        'answers',
        'dimension_scores',
        'dimension_notes',
        'total_score',
        'band',
        'is_unlocked',
        'unlocked_at',
        'ai_report',
        'report_status',
        'report_error',
        'status',
        'pic_team_id',
        'internal_note',
    ];

    protected $casts = [
        'answers' => 'array',
        'dimension_scores' => 'array',
        'dimension_notes' => 'array',
        'ai_report' => 'array',
        'total_score' => 'float',
        'is_unlocked' => 'boolean',
        'unlocked_at' => 'datetime',
    ];

    public function track()
    {
        return $this->belongsTo(AssessmentTrack::class, 'track_id');
    }

    public function orders()
    {
        return $this->hasMany(AssessmentOrder::class, 'assessment_result_id');
    }

    public function latestOrder()
    {
        return $this->hasOne(AssessmentOrder::class, 'assessment_result_id')->latestOfMany();
    }

    /** Label status pembayaran untuk guest & admin. */
    public function paymentLabel(): string
    {
        if ($this->is_unlocked) {
            return 'Lunas';
        }

        return match ($this->latestOrder?->status) {
            'expired' => 'Pembayaran kedaluwarsa',
            'failed' => 'Pembayaran gagal',
            'refunded' => 'Dana dikembalikan',
            default => 'Menunggu pembayaran',
        };
    }

    public function latestPaidOrder()
    {
        return $this->hasOne(AssessmentOrder::class, 'assessment_result_id')->where('status', 'paid')->latestOfMany();
    }

    public function pic()
    {
        return $this->belongsTo(OurTeam::class, 'pic_team_id');
    }
}
