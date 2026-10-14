<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Kode voucher diskon asesmen. Terpakai = jumlah invoice lunas yang memakai voucher ini.
 */
class AssessmentVoucher extends Model
{
    public const TYPES = [
        'free' => 'Gratis (100%)',
        'percent' => 'Diskon persen (%)',
        'fixed' => 'Potongan nominal (Rp)',
    ];

    protected $fillable = [
        'code',
        'batch',
        'description',
        'type',
        'value',
        'track_ids',
        'max_uses',
        'starts_at',
        'expires_at',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'value' => 'integer',
        'track_ids' => 'array',
        'max_uses' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /** Huruf/angka yang tidak mirip satu sama lain (tanpa 0/O, 1/I/L) untuk kode hasil generate. */
    private const CODE_ALPHABET = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

    /** Kode acak unik, mis. "DESA-7KQ2MX" (dengan awalan) atau "7KQ2MXRA". */
    public static function generateCode(?string $prefix = null, int $length = 6): string
    {
        $prefix = self::normalizeCode($prefix);
        do {
            $random = '';
            for ($i = 0; $i < $length; $i++) {
                $random .= self::CODE_ALPHABET[random_int(0, strlen(self::CODE_ALPHABET) - 1)];
            }
            $code = $prefix !== '' ? $prefix.'-'.$random : $random;
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public static function normalizeCode(?string $code): string
    {
        return strtoupper(preg_replace('/\s+/', '', (string) $code));
    }

    public function orders()
    {
        return $this->hasMany(AssessmentOrder::class, 'voucher_id');
    }

    public function paidOrders()
    {
        return $this->orders()->where('status', 'paid');
    }

    public function usedCount(): int
    {
        return (int) ($this->paid_orders_count ?? $this->paidOrders()->count());
    }

    public function appliesTo(AssessmentTrack $track): bool
    {
        return empty($this->track_ids) || in_array($track->id, array_map('intval', $this->track_ids), true);
    }

    /** Potongan (Rp) untuk harga tertentu, tidak melebihi harga. */
    public function discountFor(int $price): int
    {
        $discount = match ($this->type) {
            'free' => $price,
            'percent' => (int) round($price * min(100, $this->value) / 100),
            default => $this->value,
        };

        return max(0, min($price, $discount));
    }

    /** Label singkat, mis. "Gratis", "20%", "Rp 50.000". */
    public function label(): string
    {
        return match ($this->type) {
            'free' => __('Gratis'),
            'percent' => $this->value.'%',
            default => 'Rp '.number_format($this->value, 0, ',', '.'),
        };
    }

    /**
     * Alasan voucher tidak bisa dipakai untuk jalur ini, atau null bila valid.
     */
    public function rejectionFor(AssessmentTrack $track): ?string
    {
        return match (true) {
            ! $this->is_active => __('Kode voucher tidak aktif.'),
            $this->starts_at && $this->starts_at->isFuture() => __('Kode voucher belum berlaku.'),
            $this->expires_at && $this->expires_at->isPast() => __('Kode voucher sudah kedaluwarsa.'),
            ! $this->appliesTo($track) => __('Kode voucher tidak berlaku untuk jalur asesmen ini.'),
            $this->max_uses !== null && $this->usedCount() >= $this->max_uses => __('Kuota kode voucher sudah habis.'),
            default => null,
        };
    }

    /** Status untuk daftar admin. */
    public function statusLabel(): string
    {
        return match (true) {
            ! $this->is_active => 'Nonaktif',
            $this->expires_at && $this->expires_at->isPast() => 'Kedaluwarsa',
            $this->starts_at && $this->starts_at->isFuture() => 'Terjadwal',
            $this->max_uses !== null && $this->usedCount() >= $this->max_uses => 'Kuota habis',
            default => 'Aktif',
        };
    }
}
