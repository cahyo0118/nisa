{!! $php_prefix !!}

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

@if($relation->relation_type == "belongstomany")
class Create{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{!! str_singular($relation->local_table->name) !!}_{!! str_singular($relation->table->name) !!}', function (Blueprint $table) {
            $table->integer('{!! str_singular($relation->local_table->name) !!}_id')->unsigned();
            $table->integer('{!! str_singular($relation->table->name) !!}_id')->unsigned();

            $table->foreign('{!! str_singular($relation->local_table->name) !!}_id')->references('{!! $relation->local_key_field->name !!}')->on('{!! $relation->local_table->name !!}')->onDelete('cascade');
            $table->foreign('{!! str_singular($relation->table->name) !!}_id')->references('{!! $relation->foreign_key_field->name !!}')->on('{!! $relation->table->name !!}')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{!! str_singular($relation->local_table->name) !!}_{!! str_singular($relation->table->name) !!}');
    }
}
@elseif($relation->relation_type == "hasmany")
class AddForeign{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
@elseif($relation->relation_type == "belongsto")
class AddForeign{!! ucfirst(camel_case(str_singular($relation->local_table->name))) !!}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('{!! $relation->local_table->name !!}', function (Blueprint $table) {
            $table->foreign('{!! $relation->field->name !!}')->references('{!! $relation->foreign_key_field->name !!}')->on('{!! $relation->table->name !!}')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('{!! $relation->local_table->name !!}', function (Blueprint $table) {
            $table->dropForeign(['{!! $relation->field->name !!}']);
        });
    }
}
@endif
