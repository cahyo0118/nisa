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
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($menu->name))) !!} data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
@if(count($menu->table->fields()->where('searchable', true)->get()) > 0)
        ${!! str_plural($menu->name) !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
@if($field_index == 0)@if(!empty($field->relation))whereHas('{!! $field->relation->table->name !!}', function ($query) use ($keyword) {
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
            'data' => ${!! str_plural($menu->name) !!},
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
            'message' => 'Awesome, successfully update {!! title_case(str_replace('_', ' ', $menu->name)) !!} !',
        ], 200);

    }

    public function destroy($id)
    {
        ${!! $menu->name !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::find($id);

        if (${!! $menu->name !!} === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => '{!! title_case(str_replace('_', ' ', $menu->name)) !!} not found !',
            ], 400);

        }

        ${!! $menu->name !!}->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete {!! title_case(str_replace('_', ' ', $menu->name)) !!} !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        ${!! str_plural($menu->name) !!} = {!! ucfirst(str_singular($menu->table->name)) !!}::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete {!! title_case(str_replace('_', ' ', str_plural($menu->name))) !!} !',
        ], 200);

    }
@endif
}
