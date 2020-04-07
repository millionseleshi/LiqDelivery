<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable(false);
            $table->string('last_name')->nullable(false);
            $table->string('user_name')->nullable(true)->unique('user_username_index');
            $table->string('email')->nullable(true)->unique('user_email_index');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->string('alternative_phone_number')->nullable(true);
            $table->string('role')->nullable(false);
            $table->bigInteger('address_id')->nullable(true);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('address_id')
                ->on('addresses')
                ->references('id')->onDelete('cascade');
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
        Schema::dropIfExists('users', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
        });
    }
}
