<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('customer_address_id');
            $table->integer('subtotal')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('ship')->default(0);
            $table->integer('total')->default(0);
            $table->text('comment')->nullable();
            $table->string('payment_method',100)->nullable();
            $table->integer('payment_status')->default(0);
            $table->integer('shipping_status')->default(0);
            $table->string('active')->default('pending');
            $table->string('transaction',100)->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('customer_address_id')->references('id')->on('customer_addresses')->onDelete('cascade');

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
