<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('table_id')->unsigned();
            $table->string('name');
            $table->string('display_name');
            $table->string('type');
            $table->string('input_type');
            $table->string('length');
            $table->string('index')->nullable();
            $table->string('default')->nullable();
            $table->boolean('notnull')->default(false);
            $table->boolean('unsigned')->default(false);
            $table->boolean('ai')->default(false);
            $table->timestamps();

            $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
