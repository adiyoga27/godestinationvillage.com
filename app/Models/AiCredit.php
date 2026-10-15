<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Kredit layanan AI per penyedia (saldo, pengali tarif, harga token per 1 juta, US$).
 * Semua nilai diatur langsung di tabel ai_credits.
 */
class AiCredit extends Model
{
    protected $fillable = ['driver', 'balance', 'multiplier', 'price_input', 'price_cache_hit', 'price_output'];

    /** @return array{cost: float, charged: float} biaya token dan jumlah yang dibebankan (US$). */
    public static function price(string $driver, int $prompt, int $cacheHit, int $completion): array
    {
        $c = self::firstWhere('driver', $driver);
        if (! $c) {
            return ['cost' => 0.0, 'charged' => 0.0];
        }

        // DeepSeek: prompt_tokens sudah termasuk cache hit. Claude: input_tokens belum termasuk cache read.
        $fresh = $driver === 'claude' ? $prompt : max(0, $prompt - $cacheHit);
        $cost = ($fresh * $c->price_input + $cacheHit * $c->price_cache_hit + $completion * $c->price_output) / 1_000_000;

        return ['cost' => $cost, 'charged' => $cost * $c->multiplier];
    }

    public static function deduct(string $driver, float $amount): void
    {
        if ($amount > 0) {
            DB::table('ai_credits')->where('driver', $driver)->update(['balance' => DB::raw('balance - '.number_format($amount, 6, '.', ''))]);
        }
    }
}
