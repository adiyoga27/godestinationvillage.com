<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('package_id');
            $table->foreign('package_id')->references('id')->on('packages');
            $table->string('lang', 20);
            $table->string('name');
            $table->text('desc')->nullable();
            $table->text('itenaries')->nullable();
            $table->text('inclusion')->nullable();
            $table->text('exclusion')->nullable();
            $table->text('term')->nullable();
            $table->text('duration')->nullable();
            $table->text('preparation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_translations');
    }
}
