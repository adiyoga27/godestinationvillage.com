<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('tag_id')->nullable();;
            $table->string('name');
            $table->text('desc')->nullable();
            $table->text('review')->nullable();
            $table->text('itenaries')->nullable();
            $table->text('inclusion')->nullable();
            $table->text('exclusion')->nullable();
            $table->text('term')->nullable();
            $table->text('duration')->nullable();
            $table->text('preparation')->nullable();
            $table->double('price', 11, 2)->nullable();
            $table->text('default_img')->nullable();
            $table->tinyInteger('is_active')->nullable();
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
        Schema::dropIfExists('packages');
    }
}
