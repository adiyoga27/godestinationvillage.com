<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('event_id')->nullable();
            $table->unsignedInteger('bank_account_id')->nullable();
            $table->string('code')->nullable();
            $table->string('customer_name')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('event_name');
            $table->double('event_price');
            $table->double('event_discount')->nullable();
            $table->double('total_payment');
            $table->string('payment_type',100)->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('payment_status', ['pending', 'success', 'cancel'])->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->text('payment_img')->nullable();
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
        Schema::dropIfExists('order_events');
    }
}
