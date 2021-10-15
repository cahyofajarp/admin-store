<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldSizeIdOnProductGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_galleries', function (Blueprint $table) {
            $table->dropForeign('product_galleries_size_id_foreign');
            $table->dropColumn('size_id');

            $table->string('color_key')->after('color_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_galleries', function (Blueprint $table) {
            
            $table->unsignedBigInteger('size_id')->nullable()->after('product_id');

            $table->foreign('size_id')->references('id')
            ->on('sizes');

            $table->dropColumn('color_key');
        });
    }
}
