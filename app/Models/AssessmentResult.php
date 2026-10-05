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
        'source',
        'created_by',
        'name',
        'email',
        'phone',
        'organization',
        'institution',
        'business_type',
        'business_sector',
        'member_count',
        'subdistrict',
        'district',
        'regency',
        'province',
        'postal_code',
        'profile_description',
        'consent_terms_at',
        'consent_contact',
        'consent_research',
        'consent_ip',
        'answers',
        'dimension_scores',
        'dimension_notes',
        'total_score',
        'band',
        'computed_result',
        'is_unlocked',
        'approved_by',
        'approved_at',
        'approval_note',
        'unlocked_at',
        'ai_report',
        'ai_prompt',
        'ai_meta',
        'email_log',
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
        'computed_result' => 'array',
        'ai_meta' => 'array',
        'email_log' => 'array',
        'total_score' => 'float',
        'is_unlocked' => 'boolean',
        'unlocked_at' => 'datetime',
        'approved_at' => 'datetime',
        'consent_terms_at' => 'datetime',
        'consent_contact' => 'boolean',
        'consent_research' => 'boolean',
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
            return match (true) {
                $this->source === 'admin' => 'Input admin (tanpa bayar)',
                $this->approved_by !== null => 'Lunas (approve manual)',
                default => 'Lunas',
            };
        }

        return match ($this->latestOrder?->status) {
            'expired' => 'Pembayaran kedaluwarsa',
            'failed' => 'Pembayaran gagal',
            'refunded' => 'Dana dikembalikan',
            default => 'Menunggu pembayaran',
        };
    }

    public const EMAIL_TYPES = [
        'invoice' => 'Invoice',
        'paid' => 'Pembayaran berhasil',
        'report' => 'Hasil & strategi',
    ];

    /**
     * Jenis email yang boleh dikirim (ulang) sesuai status saat ini.
     * Invoice hanya selama belum lunas; bukti lunas & hasil hanya setelah lunas/terbuka.
     *
     * @return array<string, string> [type => label]
     */
    public function sendableEmails(): array
    {
        if (blank($this->email)) {
            return [];
        }

        $order = $this->relationLoaded('latestOrder') ? $this->latestOrder : $this->latestOrder()->first();

        return array_filter([
            // Belum ada invoice? Dibuat otomatis saat dikirim (lihat resendEmail).
            'invoice' => ! $this->is_unlocked && ($order || (int) ($this->track->price ?? 0) > 0) ? self::EMAIL_TYPES['invoice'] : null,
            'paid' => $this->is_unlocked && ($this->relationLoaded('latestPaidOrder') ? $this->latestPaidOrder : $this->latestPaidOrder()->first()) ? self::EMAIL_TYPES['paid'] : null,
            'report' => $this->is_unlocked && ! empty($this->ai_report) ? self::EMAIL_TYPES['report'] : null,
        ]);
    }

    /** Entri terakhir riwayat email (untuk penanda gagal/terkirim/simulasi). */
    public function lastEmail(): ?array
    {
        $log = $this->email_log ?? [];

        return $log ? end($log) : null;
    }

    /** Laporan AI boleh dicoba ulang: gagal, atau macet di status "diproses" lebih dari 3 menit. */
    public function canRetryReport(): bool
    {
        if (! empty($this->ai_report)) {
            return false;
        }

        return $this->report_status === 'failed'
            || ($this->report_status === 'generating' && $this->updated_at?->lt(now()->subMinutes(3)));
    }

    public function latestPaidOrder()
    {
        return $this->hasOne(AssessmentOrder::class, 'assessment_result_id')->where('status', 'paid')->latestOfMany();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function pic()
    {
        return $this->belongsTo(OurTeam::class, 'pic_team_id');
    }
}
