<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certification', function (Blueprint $table) {
            $table->id();
            $table->string('category',150);
            $table->string('reference_number',150);
            $table->date('date_at');
            $table->string('regarding', 255);
            $table->string('signer', 100);
            $table->string('departemen', 150);
            $table->string('addressed_to', 150);
            $table->string('file', 225);
            $table->string('slug', 150);

            $table->boolean('isActive')->default(1);
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
        Schema::dropIfExists('certification');
    }
}
