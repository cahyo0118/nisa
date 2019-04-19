{!! $php_prefix !!}

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
