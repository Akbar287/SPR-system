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
            $table->bigIncrements('id');
            $table->string('name', 128);
            $table->tinyInteger('religion');
            $table->tinyInteger('gender');
            $table->tinyInteger('roles');
            $table->string('image', 128)->nullable();
            $table->string('phone', 16)->nullable();
            $table->string('address', 255)->unique();
            $table->string('email', 255)->unique();
            $table->string('username', 32)->unique();
            $table->string('password', 255);
            $table->tinyInteger('is_active');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
