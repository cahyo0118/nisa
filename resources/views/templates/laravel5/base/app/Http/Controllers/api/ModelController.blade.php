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
        $data = QueryHelpers::getData($request, new {!! ucfirst(str_singular($menu->table->name)) !!});

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

    public function getAllByKeyword($keyword)
    {
@if(count($menu->table->fields()->where('searchable', true)->get()) > 0)
        ${!! snake_case(str_plural($menu->name)) !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
@if($field_index == 0)@if(!empty($field->relation))whereHas('{!! str_singular($field->relation->table->name) !!}', function ($query) use ($keyword) {
            $query->where('{!! $field->relation->foreign_key_field->name !!}', 'like', '%' . $keyword . '%');
        })
@else
where('{!! $field->name !!}', 'like', '%' . $keyword . '%')
@endif
@endif
@if($field_index > 0)
@if(!empty($field->relation))
            ->orWhereHas('{!! $field->relation->table->name !!}', function ($query) use ($keyword) {
                $query->where('{!! $field->relation->foreign_key_display_field->name !!}', 'like', '%' . $keyword . '%');
            })
@else
            ->orWhere('{!! $field->name !!}', 'like', '%' . $keyword . '%')
@endif
@endif
@endforeach
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => ${!! snake_case(str_plural($menu->name)) !!},
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
