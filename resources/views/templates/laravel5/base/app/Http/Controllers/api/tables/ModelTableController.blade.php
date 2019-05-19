{!! $php_prefix !!}

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
@if(!empty($table))
use App\{!! ucfirst(str_singular($table->name)) !!};
@endif
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;

class {!! ucfirst(camel_case($table->name)) !!}TableController extends Controller
{

@if(!empty($table))
    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new {!! ucfirst(str_singular($table->name)) !!});

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($table->name))) !!} data !',
        ], 200);
    }

    public function getDataSet(Request $request)
    {
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
        $data = {!! ucfirst(str_singular($table->name)) !!}::select('{!! $field->relation->foreign_key_display_field->name !!}', '{!! $field->relation->foreign_key_field->name !!}')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($table->name))) !!} data !',
        ], 200);
@endif
@endif
    }

    public function getAllByKeyword($keyword)
    {
@if(count($table->fields()->where('searchable', true)->get()) > 0)
        ${!! snake_case(str_plural($table->name)) !!} = {!! ucfirst(str_singular($table->name)) !!}::@foreach($table->fields()->where('searchable', true)->get() as $field_index => $field)
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
            'data' => ${!! snake_case(str_plural($table->name)) !!},
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($table->name))) !!} data !',
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
        ${!! snake_case(str_singular($table->name)) !!} = {!! ucfirst(str_singular($table->name)) !!}::find($id);

        // Data not found
        if (${!! snake_case(str_singular($table->name)) !!} === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => ${!! snake_case(str_singular($table->name)) !!},
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_singular($table->name))) !!} data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
@foreach($table->fields as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@else
            "{!! $field->name !!}" => "@php echo str_replace(
            ' ',
            '',
            \App\DefaultHelpers::render(\Illuminate\Support\Facades\Blade::compileString('
            @if($field->notnull)required @endif
            @if($field->index == "unique")|unique:{!! $table->name !!}, {!! $field->name !!} @endif
            @if($field->length > 0)|max:{!! $field->length !!} @endif
            @if($field->input_type == "email")|email @endif
            @if($field->input_type == "number")|numeric @endif
            @if($field->input_type == "url")|url @endif
            '),
            ['field' => $field, 'table' => $table])) @endphp",
@endif
@endforeach
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new {!! $table->name !!} !',
            ], 400);
        }

        ${!! snake_case($table->name) !!} = new {!! ucfirst(str_singular($table->name)) !!};

@foreach($table->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        ${!! snake_case($table->name) !!}->{!! $field->name !!} = Auth::id();
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        ${!! snake_case($table->name) !!}->{!! $field->name !!} = Hash::make($request->{!! $field->name !!});
@elseif($field->type == "varchar")
        ${!! snake_case($table->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@else
        ${!! snake_case($table->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@endif
@endforeach
        ${!! snake_case($table->name) !!}->save();

        return response()->json([
            'success' => true,
            'data' => ${!! snake_case($table->name) !!},
            'message' => 'Awesome, successfully create new {!! title_case(str_replace('_', ' ', $table->name)) !!} !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
@foreach($table->fields as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@else
            "{!! $field->name !!}" => "@php echo str_replace(' ', '', \App\DefaultHelpers::render(\Illuminate\Support\Facades\Blade::compileString('@if($field->notnull)required @endif @if($field->index == "unique")|unique:{!! $table->name !!}, {!! $field->name !!}, {$id} @endif @if($field->length > 0)|max:{!! $field->length !!}@endif'), ['field' => $field, 'table' => $table])) @endphp",
@endif
@endforeach
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update {!! title_case(str_replace('_', ' ', $table->name)) !!} !',
            ], 400);
        }

        ${!! snake_case($table->name) !!} = {!! ucfirst(str_singular($table->name)) !!}::find($id);

@foreach($table->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        ${!! snake_case($table->name) !!}->{!! $field->name !!} = Auth::id();
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        ${!! snake_case($table->name) !!}->{!! $field->name !!} = Hash::make($request->{!! $field->name !!});
@elseif($field->type == "varchar")
        ${!! snake_case($table->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@else
        ${!! snake_case($table->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@endif
@endforeach
        ${!! snake_case($table->name) !!}->save();

        return response()->json([
            'success' => true,
            'data' => ${!! snake_case($table->name) !!},
            'message' => 'Awesome, successfully update {!! title_case(str_replace('_', ' ', $table->name)) !!} !',
        ], 200);

    }

    public function destroy($id)
    {
        ${!! snake_case($table->name) !!} = {!! ucfirst(str_singular($table->name)) !!}::find($id);

        if (${!! snake_case($table->name) !!} === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => '{!! title_case(str_replace('_', ' ', $table->name)) !!} not found !',
            ], 400);

        }

        ${!! snake_case($table->name) !!}->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete {!! title_case(str_replace('_', ' ', $table->name)) !!} !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        ${!! snake_case(str_plural($table->name)) !!} = {!! ucfirst(str_singular($table->name)) !!}::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete {!! title_case(str_replace('_', ' ', str_plural($table->name))) !!} !',
        ], 200);

    }
@endif
}
