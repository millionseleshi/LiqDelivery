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
            $table->string('city')->nullable(true);
            $table->string('subcity')->nullable(true);
            $table->string('postal_code')->nullable(true);
            $table->string('woreda')->nullable(true);
            $table->string('kebela')->nullable(true);
            $table->string('houseno')->nullable(true);
            $table->string('special_name')->nullable(true);
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
