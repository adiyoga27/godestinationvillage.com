<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->string('slug');
        });
        Schema::table('events', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->string('slug');
        });
        Schema::table('homestay', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->string('slug');
        });
        Schema::table('post', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->string('slug');
        });

        Schema::table('village_details', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->string('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table', function (Blueprint $table) {
            //
        });
    }
}
