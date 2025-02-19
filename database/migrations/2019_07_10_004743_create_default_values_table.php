<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('value');
            $table->integer('generate_option_id')->nullable()->unsigned();
            $table->foreign('generate_option_id')->references('id')->on('generate_options')->onDelete('cascade');
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
        Schema::dropIfExists('default_values');
    }
}
