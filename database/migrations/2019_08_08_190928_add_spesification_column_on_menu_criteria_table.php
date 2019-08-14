<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpesificationColumnOnMenuCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_criteria', function (Blueprint $table) {
            $table->string('required')->nullable();
            $table->string('show_in_list')->nullable();
            $table->string('show_in_form')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_criteria', function (Blueprint $table) {
            $table->dropColumn('required');
            $table->dropColumn('show_in_list');
            $table->dropColumn('show_in_form');
        });
    }
}
