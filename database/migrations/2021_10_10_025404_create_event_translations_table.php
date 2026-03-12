<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('events_id');
            $table->foreign('events_id')->references('id')->on('events')->onDelete('cascade'); 
            $table->string('lang', 20);
            $table->string('name', 100);
            $table->text('description');
            $table->text('interary')->nullable();
            $table->text('inclusion')->nullable();
            $table->text('additional')->nullable();
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
        Schema::dropIfExists('event_translations');
    }
}
