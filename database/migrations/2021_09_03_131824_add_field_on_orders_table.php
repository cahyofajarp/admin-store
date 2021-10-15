<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldOnOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->nullable()->after('product_id')->nullable();

            $table->unsignedBigInteger('size_id')->nullable()->after('product_id')->nullable();
           
            $table->unsignedBigInteger('product_galleries_id')->after('product_id')->nullable();

            $table->foreign('product_galleries_id')->references('id')->on('product_galleries')
            ->onUpdate('cascade')->nullable();
            
            $table->foreign('color_id')->references('id')
            ->on('colors');

            $table->foreign('size_id')->references('id')
            ->on('sizes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            
            $table->dropForeign('orders_product_galleries_id_foreign');
            $table->dropColumn('product_galleries_id');

            $table->dropForeign('orders_size_id_foreign');
            $table->dropColumn('size_id');

            $table->dropForeign('orders_color_id_foreign');
            $table->dropColumn('color_id');
        });
    }
}
