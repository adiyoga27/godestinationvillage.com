<?php

namespace App\Console\Commands;

use App\Services\InstagramSyncService;
use Illuminate\Console\Command;

class SyncInstagramPosts extends Command
{
    protected $signature = 'instagram:sync';

    protected $description = 'Menyinkronkan postingan terbaru Instagram ke database (Graph API atau scrape publik)';

    public function handle(InstagramSyncService $service): int
    {
        $result = $service->sync();

        $this->info($result['message']);

        return $result['status'] === 'ok' ? self::SUCCESS : self::SUCCESS;
    }
}