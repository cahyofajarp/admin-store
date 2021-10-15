<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductgalleryThumbnailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productgallery_thumbnails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('color_id');
            $table->unsignedBigInteger('product_galleries_id');


            $table->timestamps();

            
            $table->foreign('product_id')->references('id')
            ->on('products')
            ->onDelete('cascade');

            $table->foreign('color_id')->references('id')
            ->on('colors')
            ->onDelete('cascade');

            $table->foreign('product_galleries_id')->references('id')
            ->on('product_galleries')
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
        Schema::dropIfExists('productgallery_thumbnails');
    }
}
