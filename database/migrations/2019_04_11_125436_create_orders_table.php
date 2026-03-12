<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('village_id')->nullable();
            $table->unsignedInteger('package_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('bank_account_id')->nullable();
            $table->string('code')->nullable();
            $table->string('village_name');
            $table->string('package_name');
            $table->string('customer_name')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->double('package_price')->nullable();
            $table->double('package_discount')->nullable();
            $table->double('total_payment')->nullable();
            $table->enum('payment_type', ['bank_transfer', 'paypal'])->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('payment_status', ['pending', 'success', 'cancel'])->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->text('payment_img')->nullable();
            $table->integer('pax')->nullable();
            $table->text('special_note')->nullable();
            $table->date('checkin_date')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
