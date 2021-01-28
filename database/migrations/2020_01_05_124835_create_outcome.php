<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutcome extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outcome', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_financial')->unsigned();
            $table->decimal('price', 13, 2);
            $table->decimal('discount', 13, 2);
            $table->decimal('total', 13, 2);
            $table->string('image', 128);
            $table->string('invoice', 128);
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outcome');
    }
}
