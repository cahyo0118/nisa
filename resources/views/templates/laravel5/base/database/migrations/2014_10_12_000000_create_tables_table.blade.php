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
            $table->string('{!! $field->name !!}', {!! $field->length !!}){!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!};
@elseif($field->type == "integer")
            $table->{!! $field->type !!}('{!! $field->name !!}'){!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!};
@elseif($field->type == "bigint")
            $table->bigInteger('{!! $field->name !!}', {!! $field->length !!}){!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!};
@elseif($field->type == "tinyint")
            $table->boolean('{!! $field->name !!}'){!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!};
@else
            $table->{!! $field->type !!}('{!! $field->name !!}'@if($field->length > 0), {!! $field->length !!}@endif){!! !empty($field->index) ? ("->" . $field->index . "()") : "" !!};
@endif
@endforeach
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
