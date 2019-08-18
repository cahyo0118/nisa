<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuDatasetCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_dataset_criterias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('operator')->nullable();
            $table->string('value')->nullable();
            $table->integer('menu_id')->unsigned();
            $table->integer('relation_id')->unsigned();
            $table->integer('relation_field_id')->unsigned();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('relation_id')->references('id')->on('relations')->onDelete('cascade');
            $table->foreign('relation_field_id')->references('id')->on('fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_dataset_criterias');
    }
}
