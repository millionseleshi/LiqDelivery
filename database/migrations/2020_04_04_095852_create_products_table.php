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
        Schema::enableForeignKeyConstraints();
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable(false)->unique('product_name_index');
            $table->string('product_description')->nullable(true);
            $table->string('sku');
            $table->unsignedInteger('units_in_stock');
            $table->string('product_image')->nullable(true);
            $table->integer('price_per_unit')->nullable(false)->unsigned();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('products', function (Blueprint $table) {
            $table->dropForeign('category_id');
        });
    }
}
