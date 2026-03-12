<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomestayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homestay', function (Blueprint $table) {
            $table->id();
      
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('category_homestay'); 
            $table->string('name', 100);            
            $table->text('description')->nullable();
            $table->text('location')->nullable();
            $table->double('price');
            $table->double('disc');
            $table->text('facilities')->nullable();
            $table->boolean('is_breakfast')->default(0);
            $table->text('additional_activities')->nullable();
            $table->string('owner_name', 100);
            $table->string('check_in_time', 100);
            $table->string('check_out_time', 100);
            $table->text('additional_notes')->nullable();
            $table->string('default_img', 225)->nullable();
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homestay');
    }
}
