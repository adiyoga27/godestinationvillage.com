<?php

namespace App\Console\Commands;

use App\Models\AssessmentResult;
use App\Services\AssessmentMailer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Diagnosis pengiriman email asesmen di server (mailer, cache config, SMTP, queue, riwayat).
 * Contoh: php artisan assessment:mail-check --to=nama@email.com
 */
class AssessmentMailCheck extends Command
{
    protected $signature = 'assessment:mail-check {--to= : Kirim email tes ke alamat ini}';

    protected $description = 'Periksa konfigurasi & pengiriman email asesmen (invoice, lunas, hasil)';

    public function handle(): int
    {
        $mailer = config('mail.default');
        $smtp = config('mail.mailers.smtp');
        $problems = [];

        $this->info('== Konfigurasi aktif');
        $this->table(['Kunci', 'Nilai'], [
            ['APP_ENV', config('app.env')],
            ['Config di-cache', app()->configurationIsCached() ? 'YA (ubah .env → jalankan php artisan optimize)' : 'tidak'],
            ['MAIL_MAILER', $mailer],
            ['SMTP host:port', ($smtp['host'] ?? '-').':'.($smtp['port'] ?? '-')],
            ['SMTP encryption', $smtp['encryption'] ?? ($smtp['scheme'] ?? '-')],
            ['SMTP username', $smtp['username'] ?? '-'],
            ['SMTP password', filled($smtp['password'] ?? null) ? 'terisi' : 'KOSONG'],
            ['From', config('mail.from.address').' ('.config('mail.from.name').')'],
            ['QUEUE_CONNECTION', config('queue.default')],
            ['DEEPSEEK_API_KEY', filled(config('ai.deepseek_key')) ? 'terisi' : 'KOSONG'],
        ]);

        if (AssessmentMailer::isSimulated()) {
            $problems[] = "MAIL_MAILER={$mailer}: email hanya ditulis ke log, tidak terkirim. Set MAIL_MAILER=smtp (bukan MAIL_DRIVER).";
        }
        if ($mailer === 'smtp' && ($smtp['host'] ?? '') === 'mail.godevi.org') {
            $problems[] = 'MAIL_HOST=mail.godevi.org → sertifikat TLS hanya untuk godevi.org. Gunakan MAIL_HOST=godevi.org.';
        }
        if (blank($smtp['password'] ?? null) && $mailer === 'smtp') {
            $problems[] = 'MAIL_PASSWORD kosong.';
        }
        if (config('queue.default') !== 'sync') {
            $problems[] = 'QUEUE_CONNECTION='.config('queue.default').': laporan AI masuk antrean — pastikan queue worker berjalan (Supervisor di aaPanel), atau pakai QUEUE_CONNECTION=sync.';
        }

        // Koneksi SMTP (banner server).
        if ($mailer === 'smtp' && filled($smtp['host'] ?? null)) {
            $this->info('== Tes koneksi SMTP');
            $errno = 0;
            $errstr = '';
            $fp = @fsockopen($smtp['host'], (int) $smtp['port'], $errno, $errstr, 10);
            if ($fp) {
                stream_set_timeout($fp, 10);
                $this->line('Terhubung. Banner: '.trim((string) fgets($fp)));
                fclose($fp);
            } else {
                $this->error("Tidak bisa terhubung ke {$smtp['host']}:{$smtp['port']} — {$errstr} ({$errno})");
                $problems[] = 'Koneksi SMTP gagal (port diblokir firewall/aaPanel Security, atau host salah).';
            }
        }

        // Kirim email tes.
        if ($to = $this->option('to')) {
            $this->info("== Kirim email tes ke {$to}");
            try {
                Mail::raw('Tes email asesmen GODEVI dari server ('.now()->format('d M Y H:i:s').').', function ($m) use ($to) {
                    $m->to($to)->subject('Tes Email Asesmen GODEVI');
                });
                AssessmentMailer::isSimulated()
                    ? $this->warn('Diproses, tetapi hanya ditulis ke log (mailer '.$mailer.').')
                    : $this->info('Terkirim ke server SMTP. Cek inbox / folder Spam.');
            } catch (\Throwable $e) {
                $this->error(get_class($e).': '.$e->getMessage());
                $problems[] = 'Pengiriman gagal: '.$e->getMessage();
            }
        }

        // Riwayat email terbaru.
        $this->info('== Riwayat email asesmen terbaru');
        $rows = AssessmentResult::whereNotNull('email_log')->latest('updated_at')->take(8)->get()
            ->flatMap(fn ($r) => collect($r->email_log)->map(fn ($e) => [
                '#'.$r->id,
                $e['type'] ?? '-',
                $e['to'] ?? '-',
                $e['at'] ?? '-',
                ! empty($e['simulated']) ? 'LOG SAJA' : (! empty($e['ok']) ? 'ok' : 'GAGAL'),
                mb_strimwidth((string) ($e['error'] ?? ''), 0, 70, '…'),
            ]))
            ->sortByDesc(3)->take(10)->values()->all();
        $rows ? $this->table(['Hasil', 'Jenis', 'Ke', 'Waktu', 'Status', 'Error'], $rows) : $this->line('(belum ada)');

        $noEmail = AssessmentResult::where('is_unlocked', false)->whereNull('email')->count();
        if ($noEmail) {
            $problems[] = "{$noEmail} hasil belum lunas tidak punya email (dibuat sebelum kolom email ada) — invoice tidak bisa dikirim ke hasil tersebut.";
        }

        $this->newLine();
        if ($problems) {
            $this->error('Perlu diperbaiki:');
            foreach ($problems as $p) {
                $this->line(' - '.$p);
            }

            return self::FAILURE;
        }
        $this->info('Konfigurasi email terlihat benar.');

        return self::SUCCESS;
    }
}
