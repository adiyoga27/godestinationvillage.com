<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldVillageId extends Migration
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
            $table->unsignedBigInteger('village_id')->nullable();
        });
        Schema::table('events', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->unsignedBigInteger('village_id')->nullable();

        });
        Schema::table('homestay', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->unsignedBigInteger('village_id')->nullable();

        });
        Schema::table('post', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->unsignedBigInteger('village_id')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
        $table->unsignedBigInteger('village_id')->nullable();
        });

    
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->dropColumn('village_id');

        });
        Schema::table('events', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->dropColumn('village_id');


        });
        Schema::table('homestay', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->dropColumn('village_id');


        });
        Schema::table('post', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->dropColumn('village_id');

        });
        Schema::table('users', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->dropColumn('village_id');

        });

    }
}
