<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'snap_token')) {
                $table->string('snap_token', 225)->nullable()->after('uuid');
            }
            if (! Schema::hasColumn('orders', 'pic_team_id')) {
                $table->unsignedBigInteger('pic_team_id')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('orders', 'internal_note')) {
                $table->text('internal_note')->nullable()->after('special_note');
            }
        });

        foreach (['order_events', 'order_homestay'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (! Schema::hasColumn($table->getTable(), 'pic_team_id')) {
                    $table->unsignedBigInteger('pic_team_id')->nullable()->after('user_id');
                }
                if (! Schema::hasColumn($table->getTable(), 'internal_note')) {
                    $table->text('internal_note')->nullable()->after('special_note');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'pic_team_id')) {
                $table->dropColumn('pic_team_id');
            }
            if (Schema::hasColumn('orders', 'internal_note')) {
                $table->dropColumn('internal_note');
            }
        });
        foreach (['order_events', 'order_homestay'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'pic_team_id')) {
                    $table->dropColumn('pic_team_id');
                }
                if (Schema::hasColumn($tableName, 'internal_note')) {
                    $table->dropColumn('internal_note');
                }
            });
        }
    }
};
