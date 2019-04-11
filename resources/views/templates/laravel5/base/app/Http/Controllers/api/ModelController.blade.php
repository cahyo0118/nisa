{!! $php_prefix !!}

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
@if(!empty($menu->table))
use App\{!! ucfirst(str_singular($menu->table->name)) !!};
@endif
use Illuminate\Http\Request;
use Validator;

class {!! ucfirst(camel_case($menu->name)) !!}Controller extends Controller
{

@if(!empty($menu->table))
    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new {!! ucfirst(str_singular($menu->table->name)) !!});

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! str_plural($menu->name) !!} data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        ${!! str_plural($menu->name) !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::@foreach($menu->table->fields as $field_index => $field)
@if($field_index == 0)where('{!! $field->name !!}', 'like', '%' . $keyword . '%')
@endif
@if($field_index > 0)
            ->orWhere('{!! $field->name !!}', 'like', '%' . $keyword . '%')
@endif
@endforeach
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => ${!! str_plural($menu->name) !!},
            'message' => 'Awesome, successfully get {!! str_plural($menu->name) !!} data !',
        ], 200);
    }

    public function getOne($id)
    {
        ${!! $menu->name !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::find($id);

        // Data not found
        if (${!! $menu->name !!} === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => ${!! $menu->name !!},
            'message' => 'Awesome, successfully get {!! $menu->name !!} data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
@foreach($menu->table->fields as $field_index => $field)
@if ($field->ai || $field->input_type == "hidden")
@else
            '{!! $field->name !!}' => '@if($field->notnull) required @endif @if($field->length > 0) | max:{!! $field->length !!} @endif',
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

        ${!! $menu->name !!} = new {!! ucfirst(str_singular($menu->table->name)) !!};

@foreach($menu->table->fields as $field)
@if ($field->ai || $field->input_type == "hidden")
@elseif($field->type == "varchar")
        ${!! $menu->name !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@else
        ${!! $menu->name !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@endif
@endforeach
        ${!! $menu->name !!}->save();

        return response()->json([
            'success' => true,
            'data' => ${!! $menu->name !!},
            'message' => 'Awesome, successfully create new {!! $menu->name !!} !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update {!! $menu->name !!} !',
            ], 400);
        }

        ${!! $menu->name !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::find($id);

@foreach($menu->table->fields as $field)
@if ($field->ai || $field->input_type == "hidden")
@elseif($field->type == "varchar")
        ${!! $menu->name !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@else
        ${!! $menu->name !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@endif
@endforeach
        ${!! $menu->name !!}->save();

        return response()->json([
            'success' => true,
            'data' => ${!! $menu->name !!},
            'message' => 'Awesome, successfully update {!! $menu->name !!} !',
        ], 200);

    }

    public function destroy($id)
    {
        ${!! $menu->name !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::find($id);

        if (${!! $menu->name !!} === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => '{!! ucfirst($menu->name) !!} not found !',
            ], 400);

        }

        ${!! $menu->name !!}->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete {!! $menu->name !!} !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        ${!! str_plural($menu->name) !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete {!! str_plural($menu->name) !!} !',
        ], 200);

    }
@endif
}
