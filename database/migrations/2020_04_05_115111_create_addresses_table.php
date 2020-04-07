<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('longitude')->nullable(false);
            $table->string('latitude')->nullable(false);
            $table->string('city');
            $table->string('subcity');
            $table->string('postal_code');
            $table->string('woreda');
            $table->string('kebela');
            $table->string('houseno');
            $table->string('special_name');
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
        Schema::dropIfExists('addresses');
    }
}
