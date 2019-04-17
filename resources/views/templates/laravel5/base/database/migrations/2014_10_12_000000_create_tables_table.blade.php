{!! $php_prefix !!}

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create{!! ucfirst($table->name) !!}Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('{!! $table->name !!}', function (Blueprint $table) {
@foreach($table->fields as $field)
@if ($field->ai)
            $table->increments('{!! $field->name !!}');
@elseif($field->type == "varchar")
            $table->string('{!! $field->name !!}'@if($field->length > 0), {!! $field->length !!}@endif){!! !$field->notnull ? ("->nullable()") : "" !!}{!! !empty($field->relation) ? ("->unsigned()") : "" !!}{!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!}{!! !empty($field->default) ? ("->default(" . $field->default . ")") : "" !!};
@elseif($field->type == "integer")
            $table->{!! $field->type !!}('{!! $field->name !!}'){!! !$field->notnull ? ("->nullable()") : "" !!}{!! !empty($field->relation) ? ("->unsigned()") : "" !!}{!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!}{!! !empty($field->default) ? ("->default(" . $field->default . ")") : "" !!};
@elseif($field->type == "bigint")
            $table->bigInteger('{!! $field->name !!}', {!! $field->length !!}){!! !$field->notnull ? ("->nullable()") : "" !!}{!! !empty($field->relation) ? ("->unsigned()") : "" !!}{!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!}{!! !empty($field->default) ? ("->default(" . $field->default . ")") : "" !!};
@elseif($field->type == "tinyint")
            $table->boolean('{!! $field->name !!}'){!! !$field->notnull ? ("->nullable()") : "" !!}{!! !empty($field->relation) ? ("->unsigned()") : "" !!}{!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!}{!! !empty($field->default) ? ("->default(" . $field->default . ")") : "" !!};
@else
            $table->{!! $field->type !!}('{!! $field->name !!}'@if($field->length > 0), {!! $field->length !!}@endif){!! !$field->notnull ? ("->nullable()") : "" !!}{!! !empty($field->relation) ? ("->unsigned()") : "" !!}{!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!}{!! !empty($field->default) ? ("->default(" . $field->default . ")") : "" !!};
@endif
@if(!empty($field->relation))
            $table->foreign('{!! $field->relation->field->name !!}')->references('{!! $field->relation->foreign_key_field->name !!}')->on('{!! $field->relation->table->name !!}')->onDelete('cascade');
@endif
@endforeach
@if($table->name == "users")
            $table->rememberToken();
@endif
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('{!! $table->name !!}', function (Blueprint $table) {
@foreach($table->fields as $field)
@if(!empty($field->relation))
            $table->dropForeign(['{!! $field->relation->field->name !!}']);
@endif
@endforeach
        });

        Schema::dropIfExists('{!! $table->name !!}');
    }
}
