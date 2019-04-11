{!! $php_prefix !!}

namespace App;

use Illuminate\Database\Eloquent\Model;

class {!! ucfirst(camel_case(str_singular($table->name))) !!} extends Model
{
    protected $table = "{!! $table->name !!}";

@foreach($table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    public function {!! str_singular($field->relation->table->name) !!}()
    {
        return $this->belongsTo('App\{!! ucfirst(\Illuminate\Support\Str::camel($field->relation->table->name)) !!}', '{!! $field->name !!}');
    }
@endif
@endif
@endforeach

@foreach(\App\Relation::where([
        'local_table_id' => $table->id,
        'relation_type' => 'hasmany',
    ])->orWhere([
        'local_table_id' => $table->id,
        'relation_type' => 'belongstomany',
    ])->get() as $relation)
@if($relation->relation_type == "hasmany")
    public function {!! $relation->table->name !!}()
    {
        return $this->hasMany('App\{!! ucfirst(\Illuminate\Support\Str::camel($relation->table->name)) !!}', '{!! $relation->relation_foreign_key !!}');
    }
@elseif($relation->relation_type == "belongstomany")
    public function {!! $relation->table->name !!}()
    {
        return $this->belongsToMany('App\{!! ucfirst(\Illuminate\Support\Str::camel($relation->table->name)) !!}');
    }
@endif
@endforeach
}
