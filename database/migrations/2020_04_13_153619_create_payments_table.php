<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('total_cost');
            $table->unsignedFloat('amount_paid')->default(0.00);
            $table->enum('payment_type', ['on_delivery', 'on_bank', 'deposit'])->default('on_delivery');
            $table->integer('customer_order_id')->unsigned();
            $table->foreign('customer_order_id')->references('id')->on('customer_orders')->cascadeOnDelete();
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
        Schema::dropIfExists('payments', function (Blueprint $table) {
            $table->dropForeign(['customer_order_id']);
        });
    }
}
