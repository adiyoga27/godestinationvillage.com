<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentOrder extends Model
{
    use HasFactory;

    /** Prefix kode order, dipakai webhook Midtrans untuk mengenali order asesmen. */
    public const CODE_PREFIX = 'ASM';

    protected $fillable = [
        'assessment_result_id',
        'code',
        'amount',
        'gateway',
        'gateway_ref',
        'status',
        'payment_type',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function result()
    {
        return $this->belongsTo(AssessmentResult::class, 'assessment_result_id');
    }

    public static function generateCode(): string
    {
        do {
            $code = self::CODE_PREFIX.'-'.date('Ymd').'-'.strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
