<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPriceAfterDiscountOnProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('price_after_flashsale')->after('discount')->nullable();
            $table->string('price_after_discount')->after('discount')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_after_discount');
            $table->dropColumn('price_after_flashsale');
        });
    }
}
