<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateImageUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:migrate-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates old filemanager URLs to the new secure storage path in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tables = DB::select('SHOW TABLES');
        $databaseName = DB::connection()->getDatabaseName();
        $key = "Tables_in_{$databaseName}";

        $patterns = [
            '/library/rm/source/' => '/storage/uploads/',
            '/responsive_filemanager_old/source/' => '/storage/uploads/'
        ];

        $totalReplaced = 0;

        $this->info("Starting URL migration...");

        foreach ($tables as $table) {
            $tableName = $table->$key;
            $columns = Schema::getColumnListing($tableName);

            foreach ($columns as $column) {
                foreach ($patterns as $oldPath => $newPath) {
                    try {
                        // Check if any row in this table/column has the old path
                        $count = DB::table($tableName)
                            ->where($column, 'LIKE', '%' . $oldPath . '%')
                            ->count();

                        if ($count > 0) {
                            $this->info("Found $count records in $tableName.$column for path $oldPath");
                            
                            $updated = DB::table($tableName)
                                ->where($column, 'LIKE', '%' . $oldPath . '%')
                                ->update([
                                    $column => DB::raw("REPLACE(`$column`, '$oldPath', '$newPath')")
                                ]);
                            
                            $this->line("  -> Replaced $updated records.");
                            $totalReplaced += $updated;
                        }
                    } catch (\Exception $e) {
                         // Skip silently or log debug info, mostly happens if column isn't string
                    }
                }
            }
        }

        $this->info("Migration complete. Total records updated: $totalReplaced");
        return 0;
    }
}
