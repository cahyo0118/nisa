<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticDatasetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_datasets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->string('label');
            $table->integer('field_id')->nullable()->unsigned();
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
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
        Schema::dropIfExists('static_datasets');
    }
}
