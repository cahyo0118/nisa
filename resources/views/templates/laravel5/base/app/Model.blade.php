{!! $php_prefix !!}

namespace App;

@if($table->name == "users")
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
@else
use Illuminate\Database\Eloquent\Model;
@endif

class {!! ucfirst(camel_case(str_singular($table->name))) !!} extends @if($table->name == "users")
Authenticatable
@else
Model
@endif
{
    protected $table = "{!! $table->name !!}";
@if($table->name == "users")

    use HasApiTokens, Notifiable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
@foreach($table->fields()->where('input_type', '<>', 'hidden')->get() as $field)
        '{!! $field->name !!}',
@endforeach
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'remember_token',
    ];
@endif
@foreach($table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    public function {!! str_singular($field->relation->table->name) !!}()
    {
        return $this->belongsTo('App\{!! ucfirst(camel_case(str_singular($field->relation->table->name))) !!}', '{!! $field->name !!}');
    }
@endif
@endif
@endforeach

@foreach(\App\Relation::where('local_table_id', $table->id)
            ->where('relation_type', 'hasmany')
            ->orWhere('local_table_id', $table->id)
            ->where('relation_type', 'belongstomany')
            ->get() as $relation)
@if($relation->relation_type == "hasmany")
    public function {!! $relation->table->name !!}()
    {
        return $this->hasMany('App\{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}', '{!! $relation->relation_foreign_key !!}');
    }
@elseif($relation->relation_type == "belongstomany")
    public function {!! $relation->table->name !!}()
    {
        return $this->belongsToMany('App\{!! ucfirst(camel_case(str_singular($relation->table->name))) !!}', '{!! str_singular($relation->local_table->name) !!}_{!! str_singular($relation->table->name) !!}', '{!! str_singular($relation->local_table->name) !!}_id', '{!! str_singular($relation->table->name) !!}_id');
    }
@endif
@endforeach

}
