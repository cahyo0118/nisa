{!! $php_prefix !!}

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
@if(!empty($menu->table))
use App\{!! ucfirst(str_singular($menu->table->name)) !!};
@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
use App\{!! ucfirst(camel_case(str_singular($field->relation->table->name))) !!};
@endif
@endif
@endforeach
@endif
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;

class {!! ucfirst(camel_case($menu->name)) !!}Controller extends Controller
{

@if(!empty($menu->table))
    public function getAll(Request $request)
    {
@if(count($menu->field_criterias) < 1)
        $data = QueryHelpers::getData($request, new {!! ucfirst(str_singular($menu->table->name)) !!});
@else
        $data = {!! ucfirst(str_singular($menu->table->name)) !!}::@foreach($menu->field_criterias as $criteria_index => $criteria)
@if($criteria_index == 0)@if(!empty($criteria->relation))whereHas('{!! str_singular($criteria->relation->table->name) !!}', function ($query) {
@if($criteria->relation->relation_type == "belongsto")
@if($criteria->pivot->operator == 'like%')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'like', '%{!! $criteria->pivot->value !!}%');
@elseif($criteria->pivot->operator == 'like')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'not_like')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'not like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '=')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == '!=')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '!=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'single_quotes=')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '!single_quotes=')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '!=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'in')
            $query->whereIn('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'not_in')
            $query->whereNotIn('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'between')
            $query->whereBetween('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'not_between')
            $query->whereNotBetween('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'is_null')
            $query->whereNull('{!! $criteria->relation->foreign_key_field->name !!}');
@elseif($criteria->pivot->operator == 'is_not_null')
            $query->whereNotNull('{!! $criteria->relation->foreign_key_field->name !!}');
@else
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', {!! $criteria->pivot->operator !!}, {!! $criteria->pivot->value !!});
@endif
@endif
        })
@else
@if($criteria->pivot->operator == 'like%')
where('{!! $criteria->name !!}', 'like', '%{!! $criteria->pivot->value !!}%')
@elseif($criteria->pivot->operator == 'like')
where('{!! $criteria->name !!}', 'like', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == 'not_like')
where('{!! $criteria->name !!}', 'not like', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == '=')
where('{!! $criteria->name !!}', '=', {!! $criteria->pivot->value !!})
@elseif($criteria->pivot->operator == '!=')
where('{!! $criteria->name !!}', '!=', {!! $criteria->pivot->value !!})
@elseif($criteria->pivot->operator == 'single_quotes=')
where('{!! $criteria->name !!}', '=', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == '!single_quotes=')
where('{!! $criteria->name !!}', '!=', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == 'in')
whereIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
    '{!! $v !!}',
@endforeach
])
@elseif($criteria->pivot->operator == 'not_in')
whereNotIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
    '{!! $v !!}',
@endforeach
])
@elseif($criteria->pivot->operator == 'between')
whereBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
    '{!! $v !!}',
@endforeach
])
@elseif($criteria->pivot->operator == 'not_between')
whereNotBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
    '{!! $v !!}',
@endforeach
])
@elseif($criteria->pivot->operator == 'is_null')
whereNull('{!! $criteria->name !!}')
@elseif($criteria->pivot->operator == 'is_not_null')
whereNotNull('{!! $criteria->name !!}')
@else
where('{!! $criteria->name !!}', '{!! $criteria->pivot->operator !!}', {!! $criteria->pivot->value !!})
@endif
@endif
@endif
@if($criteria_index > 0)
@if(!empty($criteria->relation))
@if($criteria->relation->relation_type == "belongsto")
            ->whereHas('{!! str_singular($criteria->relation->table->name) !!}', function ($query) use ($keyword) {
@if($criteria->pivot->operator == 'like%')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'like', '%{!! $criteria->pivot->value !!}%')
@elseif($criteria->pivot->operator == 'like')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'like', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == 'not_like')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'not like', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == '=')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '=', {!! $criteria->pivot->value !!})
@elseif($criteria->pivot->operator == '!=')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '!=', {!! $criteria->pivot->value !!})
@elseif($criteria->pivot->operator == 'single_quotes=')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '=', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == '!single_quotes=')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '!=', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == 'in')
                $query->whereIn('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                    '{!! $v !!}',
@endforeach
                ])
@elseif($criteria->pivot->operator == 'not_in')
                $query->whereNotIn('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                    '{!! $v !!}',
@endforeach
                ])
@elseif($criteria->pivot->operator == 'between')
                $query->whereBetween('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                    '{!! $v !!}',
@endforeach
                ])
@elseif($criteria->pivot->operator == 'not_between')
                $query->whereNotBetween('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                    '{!! $v !!}',
@endforeach
                ])
@elseif($criteria->pivot->operator == 'is_null')
                $query->whereNull('{!! $criteria->relation->foreign_key_field->name !!}')
@elseif($criteria->pivot->operator == 'is_not_null')
                $query->whereNotNull('{!! $criteria->relation->foreign_key_field->name !!}')
@else
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', {!! $criteria->pivot->operator !!}, {!! $criteria->pivot->value !!});
@endif
            })
@endif
@else
@if($criteria->pivot->operator == 'like%')
            ->where('{!! $criteria->name !!}', 'like', '%{!! $criteria->pivot->value !!}%')
@elseif($criteria->pivot->operator == 'like')
            ->where('{!! $criteria->name !!}', 'like', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == 'not_like')
            ->where('{!! $criteria->name !!}', 'not like', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == '=')
            ->where('{!! $criteria->name !!}', '=', {!! $criteria->pivot->value !!})
@elseif($criteria->pivot->operator == '!=')
            ->where('{!! $criteria->name !!}', '!=', {!! $criteria->pivot->value !!})
@elseif($criteria->pivot->operator == 'single_quotes=')
            ->where('{!! $criteria->name !!}', '=', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == '!single_quotes=')
            ->where('{!! $criteria->name !!}', '!=', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == 'in')
            ->whereIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ])
@elseif($criteria->pivot->operator == 'not_in')
            ->whereNotIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ])
@elseif($criteria->pivot->operator == 'between')
            ->whereBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ])
@elseif($criteria->pivot->operator == 'not_between')
            ->whereNotBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ])
@elseif($criteria->pivot->operator == 'is_null')
            ->whereNull('{!! $criteria->name !!}')
@elseif($criteria->pivot->operator == 'is_not_null')
            ->whereNotNull('{!! $criteria->name !!}')
@else
            ->where('{!! $criteria->name !!}', '{!! $criteria->pivot->operator !!}', {!! $criteria->pivot->value !!})
@endif
@endif
@endif
@endforeach
        ;

        $data = QueryHelpers::getDataByQueryBuilder($request, $data);
@endif

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($menu->name))) !!} data !',
        ], 200);
    }

@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    public function get{!! ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet(Request $request)
    {
        $data = {!! ucfirst(camel_case(str_singular($field->relation->table->name))) !!}::select('{!! $field->relation->foreign_key_display_field->name !!}', '{!! $field->relation->foreign_key_field->name !!}')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($field->relation->table->name))) !!} data !',
        ], 200);
    }
@endif
@endif
@endforeach

    public function getAllByKeyword(Request $request, $keyword)
    {
@if(count($menu->table->fields()->where('searchable', true)->get()) > 0)
        $data = {!! ucfirst(str_singular($menu->table->name)) !!}::@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
@if($field_index == 0)@if(!empty($field->relation))whereHas('{!! str_singular($field->relation->table->name) !!}', function ($query) use ($keyword) {
            $query->where('{!! $field->relation->foreign_key_field->name !!}', 'like', '%' . $keyword . '%');
@foreach($menu->field_criterias as $criteria_index => $criteria)
@if($criteria->pivot->operator == 'like%')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'like', '%{!! $criteria->pivot->value !!}%');
@elseif($criteria->pivot->operator == 'like')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'not_like')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'not like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '=')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == '!=')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '!=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'single_quotes=')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '!single_quotes=')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '!=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'in')
            $query->whereIn('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'not_in')
            $query->whereNotIn('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'between')
            $query->whereBetween('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'not_between')
            $query->whereNotBetween('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'is_null')
            $query->whereNull('{!! $criteria->relation->foreign_key_field->name !!}');
@elseif($criteria->pivot->operator == 'is_not_null')
            $query->whereNotNull('{!! $criteria->relation->foreign_key_field->name !!}');
@else
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', {!! $criteria->pivot->operator !!}, {!! $criteria->pivot->value !!});
@endif
@endforeach
        })
@else
where('{!! $field->name !!}', 'like', '%' . $keyword . '%')
@foreach($menu->field_criterias as $criteria_index => $criteria)
@if($criteria->pivot->operator == 'like%')
            ->where('{!! $criteria->name !!}', 'like', '%{!! $criteria->pivot->value !!}%')
@elseif($criteria->pivot->operator == 'like')
            ->where('{!! $criteria->name !!}', 'like', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == 'not_like')
            ->where('{!! $criteria->name !!}', 'not like', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == '=')
            ->where('{!! $criteria->name !!}', '=', {!! $criteria->pivot->value !!})
@elseif($criteria->pivot->operator == '!=')
            ->where('{!! $criteria->name !!}', '!=', {!! $criteria->pivot->value !!})
@elseif($criteria->pivot->operator == 'single_quotes=')
            ->where('{!! $criteria->name !!}', '=', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == '!single_quotes=')
            ->where('{!! $criteria->name !!}', '!=', '{!! $criteria->pivot->value !!}')
@elseif($criteria->pivot->operator == 'in')
            ->whereIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ])
@elseif($criteria->pivot->operator == 'not_in')
            ->whereNotIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ])
@elseif($criteria->pivot->operator == 'between')
            ->whereBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ])
@elseif($criteria->pivot->operator == 'not_between')
            ->whereNotBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ])
@elseif($criteria->pivot->operator == 'is_null')
            ->whereNull('{!! $criteria->name !!}')
@elseif($criteria->pivot->operator == 'is_not_null')
            ->whereNotNull('{!! $criteria->name !!}')
@else
            ->where('{!! $criteria->name !!}', '{!! $criteria->pivot->operator !!}', {!! $criteria->pivot->value !!})
@endif
@endforeach
@endif
@endif
@if($field_index > 0)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
            ->orWhereHas('{!! str_singular($field->relation->table->name) !!}', function ($query) use ($keyword) {
                $query->where('{!! $field->relation->foreign_key_display_field->name !!}', 'like', '%' . $keyword . '%');
@foreach($menu->field_criterias as $criteria_index => $criteria)
@if($criteria->pivot->operator == 'like%')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'like', '%{!! $criteria->pivot->value !!}%');
@elseif($criteria->pivot->operator == 'like')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'not_like')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', 'not like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '=')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == '!=')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '!=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'single_quotes=')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '!single_quotes=')
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '!=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'in')
                $query->whereIn('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                    '{!! $v !!}',
@endforeach
                ]);
@elseif($criteria->pivot->operator == 'not_in')
                $query->whereNotIn('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                    '{!! $v !!}',
@endforeach
                ]);
@elseif($criteria->pivot->operator == 'between')
                $query->whereBetween('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                    '{!! $v !!}',
@endforeach
                ]);
@elseif($criteria->pivot->operator == 'not_between')
                $query->whereNotBetween('{!! $criteria->relation->foreign_key_field->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                    '{!! $v !!}',
@endforeach
                ]);
@elseif($criteria->pivot->operator == 'is_null')
                $query->whereNull('{!! $criteria->relation->foreign_key_field->name !!}');
@elseif($criteria->pivot->operator == 'is_not_null')
                $query->whereNotNull('{!! $criteria->relation->foreign_key_field->name !!}');
@else
                $query->where('{!! $criteria->relation->foreign_key_field->name !!}', {!! $criteria->pivot->operator !!}, {!! $criteria->pivot->value !!});
@endif
@endforeach
            })
@endif
@else
            ->orWhere('{!! $field->name !!}', 'like', '%' . $keyword . '%')
@endif
@endif
@endforeach
            ;

        $data = QueryHelpers::getDataByQueryBuilder($request, $data);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($menu->name))) !!} data !',
        ], 200);
@else
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'This menu cannot be searched',
        ], 400);
@endif
    }

    public function getOne($id)
    {
        ${!! snake_case($menu->name) !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::find($id);

        // Data not found
        if (${!! snake_case($menu->name) !!} === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => ${!! snake_case($menu->name) !!},
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', $menu->name)) !!} data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
@foreach($menu->table->fields as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@else
            "{!! $field->name !!}" => "@php echo str_replace(
            ' ',
            '',
            \App\DefaultHelpers::render(\Illuminate\Support\Facades\Blade::compileString('
            @if($field->notnull)required @endif
            @if($field->index == "unique")|unique:{!! $menu->table->name !!}, {!! $field->name !!} @endif
            @if($field->length > 0)|max:{!! $field->length !!} @endif
            @if($field->input_type == "email")|email @endif
            @if($field->input_type == "number")|numeric @endif
            @if($field->input_type == "url")|url @endif
            '),
            ['field' => $field, 'menu' => $menu])) @endphp",
@endif
@endforeach
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new {!! $menu->name !!} !',
            ], 400);
        }

        ${!! snake_case($menu->name) !!} = new {!! ucfirst(str_singular($menu->table->name)) !!};

@foreach($menu->table->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = Auth::id();
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = Hash::make($request->{!! $field->name !!});
@elseif($field->type == "varchar")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@else
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@endif
@endforeach
        ${!! snake_case($menu->name) !!}->save();

        return response()->json([
            'success' => true,
            'data' => ${!! snake_case($menu->name) !!},
            'message' => 'Awesome, successfully create new {!! title_case(str_replace('_', ' ', $menu->name)) !!} !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
@foreach($menu->table->fields as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@else
            "{!! $field->name !!}" => "@php echo str_replace(' ', '', \App\DefaultHelpers::render(\Illuminate\Support\Facades\Blade::compileString('@if($field->notnull)required @endif @if($field->index == "unique")|unique:{!! $menu->table->name !!}, {!! $field->name !!}, {$id} @endif @if($field->length > 0)|max:{!! $field->length !!}@endif'), ['field' => $field, 'menu' => $menu])) @endphp",
@endif
@endforeach
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update {!! title_case(str_replace('_', ' ', $menu->name)) !!} !',
            ], 400);
        }

        ${!! snake_case($menu->name) !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::find($id);

@foreach($menu->table->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = Auth::id();
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = Hash::make($request->{!! $field->name !!});
@elseif($field->type == "varchar")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@else
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@endif
@endforeach
        ${!! snake_case($menu->name) !!}->save();

        return response()->json([
            'success' => true,
            'data' => ${!! snake_case($menu->name) !!},
            'message' => 'Awesome, successfully update {!! title_case(str_replace('_', ' ', $menu->name)) !!} !',
        ], 200);

    }

    public function destroy($id)
    {
        ${!! snake_case($menu->name) !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::find($id);

        if (${!! snake_case($menu->name) !!} === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => '{!! title_case(str_replace('_', ' ', $menu->name)) !!} not found !',
            ], 400);

        }

        ${!! snake_case($menu->name) !!}->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete {!! title_case(str_replace('_', ' ', $menu->name)) !!} !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        ${!! snake_case(str_plural($menu->name)) !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete {!! title_case(str_replace('_', ' ', str_plural($menu->name))) !!} !',
        ], 200);

    }
@endif
}
