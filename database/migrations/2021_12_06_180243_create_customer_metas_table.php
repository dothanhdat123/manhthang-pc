<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_metas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->comment('Sub key');
            $table->string('meta_key', 255);
            $table->longText('meta_value')->nullable();
            $table->timestamps();

            if (Schema::hasTable('customers')) {
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('customers')) {
            Schema::table('customer_metas', function (Blueprint $table) {
                $table->dropForeign('customer_metas_customer_id_foreign');
            });
        }

        Schema::dropIfExists('customer_metas');
    }
}
