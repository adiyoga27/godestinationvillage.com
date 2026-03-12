<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditOrdersUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->uuid('uuid');
        });
        Schema::table('order_events', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->uuid('uuid');
        });
        Schema::table('order_homestay', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->uuid('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
