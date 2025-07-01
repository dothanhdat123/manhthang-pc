<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sku')->unique();
            $table->string('name', 255);
            $table->text('slug');
            $table->text('description');
            $table->longText('content')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('unit_price')->default(0);
            $table->integer('sale')->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->integer('price')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('supplier_id');
            $table->text('thumb')->nullable();
            $table->longText('image')->nullable();
            $table->integer('views')->default(0);
            $table->string('seo_title', 191)->nullable();
            $table->string('seo_description', 255)->nullable();
            $table->text('seo_keyword')->nullable();
            $table->string('active')->default('draft');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
