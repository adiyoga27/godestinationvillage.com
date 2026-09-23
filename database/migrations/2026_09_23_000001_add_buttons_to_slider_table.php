<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slider', function (Blueprint $table) {
            $table->string('button_label', 191)->nullable()->after('desc_id');
            $table->string('button_label_id', 191)->nullable()->after('button_label');
            $table->string('button_url', 500)->nullable()->after('button_label_id');
            $table->string('button_color', 20)->nullable()->after('button_url');
            $table->string('button2_label', 191)->nullable()->after('button_color');
            $table->string('button2_label_id', 191)->nullable()->after('button2_label');
            $table->string('button2_url', 500)->nullable()->after('button2_label_id');
            $table->string('button2_color', 20)->nullable()->after('button2_url');
        });
    }

    public function down(): void
    {
        Schema::table('slider', function (Blueprint $table) {
            $table->dropColumn([
                'button_label',
                'button_label_id',
                'button_url',
                'button_color',
                'button2_label',
                'button2_label_id',
                'button2_url',
                'button2_color',
            ]);
        });
    }
};
