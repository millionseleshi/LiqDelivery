<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->string('ordered_date');
            $table->text('note')->nullable();
            $table->unsignedFloat('total_price');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('customer_orders', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
    }
}
