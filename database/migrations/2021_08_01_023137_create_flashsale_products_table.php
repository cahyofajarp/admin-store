<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashsaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flashsale_deal_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flashsale_id');
            $table->unsignedBigInteger('product_id');
            $table->string('discount');
            $table->timestamps();

            
            $table->foreign('product_id')->references('id')
            ->on('products')
            ->onDelete('cascade');

            
            $table->foreign('flashsale_id')->references('id')
            ->on('flashsales')
            ->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flashsale_deal__products');
    }
}
