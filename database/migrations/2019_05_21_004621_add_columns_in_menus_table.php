<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->boolean('allow_list')->default(true);
            $table->boolean('allow_create')->default(true);
            $table->boolean('allow_single')->default(true);
            $table->boolean('allow_update')->default(true);
            $table->boolean('allow_delete')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('allow_list');
            $table->dropColumn('allow_list');
            $table->dropColumn('allow_create');
            $table->dropColumn('allow_single');
            $table->dropColumn('allow_update');
            $table->dropColumn('allow_delete');
        });
    }
}
