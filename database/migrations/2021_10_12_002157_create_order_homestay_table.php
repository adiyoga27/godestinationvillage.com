<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHomestayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_homestay', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('homestay_id')->nullable();
            $table->string('code')->nullable();
            $table->string('customer_name')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('homestay_name');
            $table->double('homestay_price');
            $table->double('homestay_discount')->nullable();
            $table->double('total_payment');
            $table->string('payment_type',100)->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('payment_status', ['pending', 'success', 'cancel'])->nullable();
            $table->integer('pax')->nullable();
            $table->text('special_note')->nullable();
            $table->text('snap_token')->nullable();
            $table->string('uuid')->nullable();

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
        Schema::dropIfExists('order_homestay');
    }
}
