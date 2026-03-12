<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomestayTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homestay_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homestay_id');
            $table->foreign('homestay_id')->references('id')->on('homestay')->onDelete('cascade'); 
            $table->string('lang', 20);
            $table->string('name', 100);            
            $table->text('description')->nullable();
            $table->text('location')->nullable();
            $table->text('facilities')->nullable();
            $table->text('additional_activities')->nullable();
            $table->text('additional_notes')->nullable();
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
        Schema::dropIfExists('homestay_translations');
    }
}
