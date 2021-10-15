<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldOnCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('color_id')->after('customer_id')->nullable();
            $table->unsignedBigInteger('size_id')->after('customer_id')->nullable();

            
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
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('product_galleries_color_id_foreign');
            $table->dropColumn('color_id');

            $table->dropForeign('product_galleries_size_id_foreign');
            $table->dropColumn('size_id');

        });
    }
}
