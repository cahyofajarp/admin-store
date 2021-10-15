<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHightlightProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hightlight_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('product_id');
            $table->string('description');
            $table->string('image');
            $table->timestamps();

            $table->foreign('product_id')->references('id')
            ->on('products')
            ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hightlight_products');
    }
}
