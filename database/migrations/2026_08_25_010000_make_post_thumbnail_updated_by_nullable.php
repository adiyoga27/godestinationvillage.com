<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post', function (Blueprint $table) {
            $table->string('post_thumbnail')->nullable()->change();
            $table->unsignedBigInteger('updated_by')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('post', function (Blueprint $table) {
            $table->string('post_thumbnail')->nullable(false)->change();
            $table->unsignedBigInteger('updated_by')->nullable(false)->change();
        });
    }
};