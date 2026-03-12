<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->dateTime('transaction_time');
            $table->string('transaction_status',100);
            $table->string('transaction_id',225);
            $table->string('status_message',225)->nullable();
            $table->string('status_code',225)->nullable();
            $table->string('signature_key',225)->nullable();
            $table->dateTime('settlement_time')->nullable();
            $table->string('payment_type',225)->nullable();
            $table->string('order_id',225)->nullable();
            $table->string('merchant_id',225)->nullable();
            $table->double('gross_amount',225)->nullable();
            $table->string('fraud_status',225)->nullable();
            $table->string('currency',225)->nullable();
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
        Schema::dropIfExists('transaction');
    }
}
