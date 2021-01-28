<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryPeoples extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('historypeoples', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_users');
            $table->string('title', 64);
            $table->text('description');
            $table->string('icon', 64);
            $table->string('ip_address', 16);
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
        Schema::dropIfExists('historypeoples');
    }
}
