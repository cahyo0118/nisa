{!! $php_prefix !!}
@php
$relations = [];
if(!empty($menu->table)) {
    foreach($menu->table->fields as $field) {
        if(!empty($field->relation) && $field->relation->relation_type == "belongsto") {
            array_push($relations, $field->relation->table->name);
        }
    }

    $menu_relation_criterias = App\MenuRelationCriteria::where('menu_id', $menu->id)->get();

    foreach($menu_relation_criterias as $menu_relation_criteria) {
        array_push($relations, $menu_relation_criteria->relation->table->name);
    }

    $relations = array_unique($relations);

}
@endphp

namespace App\Http\Controllers\api;

use Illuminate\Database\Eloquent\Builder;
use Image;
use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
@if(!empty($menu->table))
use App\{!! ucfirst(camel_case(str_singular($menu->table->name))) !!};
@foreach($relations as $relation)
@if($relation !== $menu->table->name)
use App\{!! ucfirst(camel_case(str_singular($relation))) !!};
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
        $data = new {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}();

        $filters = json_decode($request->filters, true);

        foreach ($filters as $filter_name => $filter_value) {

            if (!empty($filter_value))
                $data = $data->where($filter_name, '=', $filter_value);

        }

@php
    if(!empty($menu->table)) {
        $menu_relation_criterias = App\MenuRelationCriteria::where('menu_id', $menu->id)->get();
    }
@endphp
@foreach($menu_relation_criterias as $menu_relation_criteria)
        $data = $data->whereHas('{!! !empty($menu_relation_criteria->relation->relation_name) ? $menu_relation_criteria->relation->relation_name : $menu_relation_criteria->relation->table->name !!}', function ($query) {
@if($menu_relation_criteria->operator == 'like%')
                $query->where('{!! $menu_relation_criteria->relation_field->name !!}', 'like', '%{!! $menu_relation_criteria->value !!}%');
@elseif($menu_relation_criteria->operator == 'like')
                $query->where('{!! $menu_relation_criteria->relation_field->name !!}', 'like', '{!! $menu_relation_criteria->value !!}');
@elseif($menu_relation_criteria->operator == 'not_like')
                $query->where('{!! $menu_relation_criteria->relation_field->name !!}', 'not like', '{!! $menu_relation_criteria->value !!}');
@elseif($menu_relation_criteria->operator == '=')
                $query->where('{!! $menu_relation_criteria->relation_field->name !!}', '=', {!! $menu_relation_criteria->value !!});
@elseif($menu_relation_criteria->operator == '!=')
                $query->where('{!! $menu_relation_criteria->relation_field->name !!}', '!=', {!! $menu_relation_criteria->value !!});
@elseif($menu_relation_criteria->operator == 'single_quotes=')
                $query->where('{!! $menu_relation_criteria->relation_field->name !!}', '=', '{!! $menu_relation_criteria->value !!}');
@elseif($menu_relation_criteria->operator == '!single_quotes=')
                $query->where('{!! $menu_relation_criteria->relation_field->name !!}', '!=', '{!! $menu_relation_criteria->value !!}');
@elseif($menu_relation_criteria->operator == 'in')
                $query->whereIn('{!! $menu_relation_criteria->relation_field->name !!}', [
@foreach(explode(',', $menu_relation_criteria->value) as $v)
                    '{!! $v !!}',
@endforeach
                ]);
@elseif($menu_relation_criteria->operator == 'not_in')
                $query->whereNotIn('{!! $menu_relation_criteria->relation_field->name !!}', [
@foreach(explode(',', $menu_relation_criteria->value) as $v)
                    '{!! $v !!}',
@endforeach
                ]);
@elseif($menu_relation_criteria->operator == 'between')
                $query->whereBetween('{!! $menu_relation_criteria->relation_field->name !!}', [
@foreach(explode(',', $menu_relation_criteria->value) as $v)
                    '{!! $v !!}',
@endforeach
                ]);
@elseif($menu_relation_criteria->operator == 'not_between')
                $query->whereNotBetween('{!! $menu_relation_criteria->relation_field->name !!}', [
@foreach(explode(',', $menu_relation_criteria->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($menu_relation_criteria->operator == 'is_null')
            $query->whereNull('{!! $menu_relation_criteria->relation_field->name !!}');
@elseif($menu_relation_criteria->operator == 'is_not_null')
            $query->whereNotNull('{!! $menu_relation_criteria->relation_field->name !!}');
@elseif($menu_relation_criteria->operator == 'default')
            $query->where('{!! $menu_relation_criteria->relation_field->name !!}', '=', @if($menu_relation_criteria->value == 'current_user_id')Auth::id()@else{!! $menu_relation_criteria->value !!}@endif);
@else
            $query->where('{!! $menu_relation_criteria->relation_field->name !!}', {!! $menu_relation_criteria->operator !!}, {!! $menu_relation_criteria->value !!});
@endif
        });
@endforeach

@foreach($menu->field_criterias as $criteria_index => $criteria)
@if(!empty($criteria->pivot->operator))
@if(!empty($criteria->relation))
@if($criteria->relation->relation_type == "belongsto")
        $data = $data->whereHas('{!! !empty($criteria->relation->relation_name) ? $criteria->relation->relation_name : str_singular($criteria->relation->table->name) !!}', function ($query) {
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
@elseif($criteria->pivot->operator == 'default')
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', '=', @if($criteria->pivot->value == 'current_user_id')Auth::id()@else{!! $criteria->pivot->value !!}@endif);
@else
            $query->where('{!! $criteria->relation->foreign_key_field->name !!}', {!! $criteria->pivot->operator !!}, {!! $criteria->pivot->value !!});
@endif
        });
@endif
@else
@if(empty($criteria->pivot->operator))
@elseif($criteria->pivot->operator == 'like%')
        $data = $data->where('{!! $criteria->name !!}', 'like', '%{!! $criteria->pivot->value !!}%');
@elseif($criteria->pivot->operator == 'like')
        $data = $data->where('{!! $criteria->name !!}', 'like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'not_like')
        $data = $data->where('{!! $criteria->name !!}', 'not like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '=')
        $data = $data->where('{!! $criteria->name !!}', '=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == '!=')
        $data = $data->where('{!! $criteria->name !!}', '!=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'single_quotes=')
        $data = $data->where('{!! $criteria->name !!}', '=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '!single_quotes=')
        $data = $data->where('{!! $criteria->name !!}', '!=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'in')
        $data = $data->whereIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'not_in')
        $data = $data->whereNotIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'between')
        $data = $data->whereBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'not_between')
        $data = $data->whereNotBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
                '{!! $v !!}',
@endforeach
            ]);
@elseif($criteria->pivot->operator == 'is_null')
        $data = $datawhereNull('{!! $criteria->name !!}');
@elseif($criteria->pivot->operator == 'is_not_null')
        $data = $datawhereNotNull('{!! $criteria->name !!}');
@else
        $data = $datawhere('{!! $criteria->name !!}', '{!! $criteria->pivot->operator !!}', {!! $criteria->pivot->value !!});
@endif
@endif

@endif
@endforeach

        $data = QueryHelpers::getDataByQueryBuilder($request, $data);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($menu->name))) !!} data !',
        ], 200);
    }

@foreach($menu->table->fields as $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    public function get{!! !empty($field->relation->relation_name) ? ucfirst(camel_case(str_plural($field->relation->relation_name))) : ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet(Request $request)
    {
        $data = new {!! ucfirst(camel_case(str_singular($field->relation->table->name))) !!}();
@php
    $menu_relation_criterias = App\MenuDatasetCriteria::where('menu_id', $menu->id)->where('relation_id', $field->relation->id)->get();
@endphp
@foreach($menu_relation_criterias as $criteria_index => $criteria)
@if(!empty($criteria->operator))
@if($criteria->operator == 'like%')
        $data = $data->where('{!! $criteria->relation_field->name !!}', 'like', '%{!! $criteria->value !!}%');
@elseif($criteria->operator == 'like')
        $data = $data->where('{!! $criteria->relation_field->name !!}', 'like', '{!! $criteria->value !!}');
@elseif($criteria->operator == 'not_like')
        $data = $data->where('{!! $criteria->relation_field->name !!}', 'not like', '{!! $criteria->value !!}');
@elseif($criteria->operator == '=')
        $data = $data->where('{!! $criteria->relation_field->name !!}', '=', {!! $criteria->value !!});
@elseif($criteria->operator == '!=')
        $data = $data->where('{!! $criteria->relation_field->name !!}', '!=', {!! $criteria->value !!});
@elseif($criteria->operator == 'single_quotes=')
        $data = $data->where('{!! $criteria->relation_field->name !!}', '=', '{!! $criteria->value !!}');
@elseif($criteria->operator == '!single_quotes=')
        $data = $data->where('{!! $criteria->relation_field->name !!}', '!=', '{!! $criteria->value !!}');
@elseif($criteria->operator == 'in')
        $data = $data->whereIn('{!! $criteria->relation_field->name !!}', [
@foreach(explode(',', $criteria->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($criteria->operator == 'not_in')
        $data = $data->whereNotIn('{!! $criteria->relation_field->name !!}', [
@foreach(explode(',', $criteria->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($criteria->operator == 'between')
        $data = $data->whereBetween('{!! $criteria->relation_field->name !!}', [
@foreach(explode(',', $criteria->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($criteria->operator == 'not_between')
        $data = $data->whereNotBetween('{!! $criteria->relation_field->name !!}', [
@foreach(explode(',', $criteria->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($criteria->operator == 'is_null')
        $data = $data->whereNull('{!! $criteria->relation_field->name !!}');
@elseif($criteria->operator == 'is_not_null')
        $data = $data->whereNotNull('{!! $criteria->relation_field->name !!}');
@else
        $data = $data->where('{!! $criteria->relation_field->name !!}', '{!! $criteria->operator !!}', {!! $criteria->value !!});
@endif
@endif
@endforeach
        $data = $data->select('{!! $field->relation->foreign_key_display_field->name !!}', '{!! $field->relation->foreign_key_field->name !!}')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($field->relation->table->name))) !!} data !',
        ], 200);
    }

@php
$field_reference = null;
$reference = DB::table('menu_load_references')->where('menu_id', $menu->id)->where('field_id', $field->id)->first();
if (!empty($reference)) {
    $field_reference = \App\Field::find($reference->field_reference_id);
}
@endphp
@if(!empty($field_reference))

    public function get{!! !empty($field->relation->relation_name) ? ucfirst(camel_case(str_plural($field->relation->relation_name))) : ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSetBy{!! ucfirst(camel_case($field_reference->name)) !!}(Request $request, ${!! snake_case($field_reference->name) !!})
    {
        $data = {!! !empty($field->relation->relation_name) ? ucfirst(camel_case(str_singular($field->relation->relation_name))) : ucfirst(camel_case(str_singular($field->relation->table->name))) !!}::where('{!! snake_case($field_reference->name) !!}', ${!! snake_case($field_reference->name) !!})->select('{!! $field->relation->foreign_key_display_field->name !!}', '{!! $field->relation->foreign_key_field->name !!}')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($field->relation->table->name))) !!} data !',
        ], 200);
    }
@endif

@endif
@endif
@endforeach

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
    public function getAll{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}Relation(Request $request, $id)
    {
        $data = {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}::find($id)->{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}();

        $filters = json_decode($request->filters, true);

        foreach ($filters as $filter_name => $filter_value) {

            if (!empty($filter_value))
                $data = $data->where($filter_name, '=', $filter_value);

        }

        $data = QueryHelpers::getDataByQueryBuilder($request, $data);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Competency Tests data !',
        ], 200);
    }

    public function add{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}(Request $request, $id)
    {
        $data = {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}::where('id', $id);

        $data = QueryHelpers::getSingleData($request, $data);

        // Data not found
        if ($data === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        if (empty($data->{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}()->where('id', $request->id)->first()))
            $data->{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}()->attach($request->id);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully add {!! !empty($relation->relation_name) ? title_case(str_singular($relation->relation_name)) : $relation->table->name !!} !',
        ], 200);

    }

    public function remove{!! !empty($relation->relation_name) ? ucfirst(camel_case(str_plural($relation->relation_name))) : ucfirst(camel_case(str_plural($relation->table->name))) !!}(Request $request, $id, ${!! !empty($relation->relation_name) ? snake_case(str_singular($relation->relation_name)) : $relation->table->name !!}_id)
    {
        $data = {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}::where('id', $id);

        $data = QueryHelpers::getSingleData($request, $data);

        // Data not found
        if ($data === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        $data->{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}()->detach(${!! !empty($relation->relation_name) ? snake_case(str_singular($relation->relation_name)) : $relation->table->name !!}_id);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully remove {!! !empty($relation->relation_name) ? title_case(str_singular($relation->relation_name)) : $relation->table->name !!} !',
        ], 200);

    }
@endif
@endforeach
@endif

    public function getAllByKeyword(Request $request, $keyword)
    {
        $data = new {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}();

        $filters = json_decode($request->filters, true);

@if(count($menu->table->fields()->where('searchable', true)->get()) > 0)
        $data = $data->@foreach($menu->table->fields()->where('searchable', true)->get() as $field_index => $field)
@if($field_index == 0)@if(!empty($field->relation))whereHas('{!! str_singular($field->relation->table->name) !!}', function ($query) use ($keyword) {
            $query->where('{!! $field->relation->foreign_key_display_field->name !!}', 'like', '%' . $keyword . '%');
@foreach($menu->field_criterias as $criteria_index => $criteria)
@if(!empty($criteria->pivot->operator))
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
@endforeach
        });

@else
where('{!! $field->name !!}', 'like', '%' . $keyword . '%');

@endif
@endif
@if($field_index > 0)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")

        foreach ($filters as $filter_name => $filter_value) {

            if (!empty($filter_value))
                $data = $data->where($filter_name, '=', $filter_value);

        }
        $data = $data->orWhereHas('{!! str_singular($field->relation->table->name) !!}', function ($query) use ($keyword) {
            $query->where('{!! $field->relation->foreign_key_display_field->name !!}', 'like', '%' . $keyword . '%');
@foreach($menu->field_criterias as $criteria_index => $criteria)
@if(!empty($criteria->pivot->operator))
@if(!empty($criteria->relation))
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
@endif
@endforeach
        });
@endif
@else

        foreach ($filters as $filter_name => $filter_value) {

            if (!empty($filter_value))
                $data = $data->where($filter_name, '=', $filter_value);

        }

        $data = $data->orWhere('{!! $field->name !!}', 'like', '%' . $keyword . '%');

@endif
@endif
@foreach($menu->field_criterias as $criteria_index => $criteria)
@if(!empty($criteria->pivot->operator))
@if($criteria->pivot->operator == 'like%')
        $data = $data->where('{!! $criteria->name !!}', 'like', '%{!! $criteria->pivot->value !!}%');
@elseif($criteria->pivot->operator == 'like')
        $data = $data->where('{!! $criteria->name !!}', 'like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'not_like')
        $data = $data->where('{!! $criteria->name !!}', 'not like', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '=')
        $data = $data->where('{!! $criteria->name !!}', '=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == '!=')
        $data = $data->where('{!! $criteria->name !!}', '!=', {!! $criteria->pivot->value !!});
@elseif($criteria->pivot->operator == 'single_quotes=')
        $data = $data->where('{!! $criteria->name !!}', '=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == '!single_quotes=')
        $data = $data->where('{!! $criteria->name !!}', '!=', '{!! $criteria->pivot->value !!}');
@elseif($criteria->pivot->operator == 'in')
        $data = $data->whereIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($criteria->pivot->operator == 'not_in')
        $data = $data->whereNotIn('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($criteria->pivot->operator == 'between')
        $data = $data->whereBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($criteria->pivot->operator == 'not_between')
        $data = $data->whereNotBetween('{!! $criteria->name !!}', [
@foreach(explode(',', $criteria->pivot->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($criteria->pivot->operator == 'is_null')
        $data = $data->whereNull('{!! $criteria->name !!}');
@elseif($criteria->pivot->operator == 'is_not_null')
        $data = $data->whereNotNull('{!! $criteria->name !!}');
@else
        $data = $data->where('{!! $criteria->name !!}', '{!! $criteria->pivot->operator !!}', {!! $criteria->pivot->value !!});
@endif
@endif
@endforeach
        foreach ($filters as $filter_name => $filter_value) {

            if (!empty($filter_value))
                $data = $data->where($filter_name, '=', $filter_value);

        }
@endforeach

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

    public function getOne(Request $request, $id)
    {
        ${!! snake_case($menu->name) !!} = {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}::where('id', $id);

        ${!! snake_case($menu->name) !!} = QueryHelpers::getSingleData($request, ${!! snake_case($menu->name) !!});

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
@php
$criteria = DB::table('menu_criteria')->where('menu_id', $menu->id)->where('field_id', $field->id)->first();
@endphp
@if((!empty($criteria) && $criteria->show_in_form) || empty($criteria))
@if ($field->ai || $field->input_type == "hidden")
@else
            "{!! $field->name !!}" => "@php echo str_replace(
            ' ',
            '',
            \App\DefaultHelpers::render(\Illuminate\Support\Facades\Blade::compileString('
            @if($field->notnull)required @endif
            @if($field->index == "unique")|unique:{!! $menu->table->name !!}, {!! $field->name !!} @endif
            @if($field->length > 0 && ($field->type == "tinyint" || $field->type == "smallint" || $field->type == "mediumint" || $field->type == "integer" || $field->type == "bigint" || $field->type == "float" || $field->type == "double" || $field->type == "decimal"))@if($field->notnull)|digits_between:1,{!! $field->length !!}@endif @endif
            @if($field->input_type == "email")|email @endif
            @if($field->input_type == "number")|numeric @endif
            @if($field->input_type == "url")|url @endif
            '),
            ['field' => $field, 'menu' => $menu])) @endphp",
@endif
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

        ${!! snake_case($menu->name) !!} = new {!! ucfirst(camel_case(str_singular($menu->table->name))) !!};

@foreach($menu->table->fields as $field)
@php
$criteria = DB::table('menu_criteria')->where('menu_id', $menu->id)->where('field_id', $field->id)->first();
@endphp
@if((!empty($criteria) && $criteria->show_in_form) || empty($criteria))
@if ($field->ai)
@elseif($field->name == "updated_by")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = Auth::id();
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = Hash::make($request->{!! $field->name !!});
@elseif($field->input_type == "image")
        if (!empty($request->{!! $field->name !!}) && !strpos($request->{!! $field->name !!}, ${!! snake_case($menu->name) !!}->{!! $field->name !!})) {
            if (${!! snake_case($menu->name) !!}->{!! $field->name !!} !== null) {
                if (file_exists(${!! snake_case($menu->name) !!}->{!! $field->name !!})) {
                    unlink(public_path(${!! snake_case($menu->name) !!}->{!! $field->name !!}));
                }
            }

            preg_match("/^data:image\/(.*);base64/", $request->{!! $field->name !!}, $ext);

            $filename = time() . '.' . $ext[1];

            if (!is_dir('{!! snake_case($menu->name) !!}/{!! str_plural($field->name) !!}/')) mkdir('{!! snake_case($menu->name) !!}/{!! str_plural($field->name) !!}/', 0777, true);

            $path = public_path('/{!! snake_case($menu->name) !!}/{!! str_plural($field->name) !!}/' . $filename);
            Image::make($request->{!! $field->name !!})->save($path);
            ${!! snake_case($menu->name) !!}->{!! $field->name !!} = '{!! snake_case($menu->name) !!}/{!! str_plural($field->name) !!}/' . $filename;
        } elseif (empty($request->{!! $field->name !!})) {
            if (${!! snake_case($menu->name) !!}->{!! $field->name !!} !== null) {
                if (file_exists(${!! snake_case($menu->name) !!}->{!! $field->name !!})) {
                    unlink(public_path(${!! snake_case($menu->name) !!}->{!! $field->name !!}));
                }
            }

            ${!! snake_case($menu->name) !!}->{!! $field->name !!} = null;
        }
@elseif($field->type == "varchar")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@else
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@endif
@endif
@endforeach
        ${!! snake_case($menu->name) !!}->save();

@php
    if(!empty($menu->table)) {
        $menu_relation_criterias = App\MenuRelationCriteria::where('menu_id', $menu->id)->get();
    }
@endphp
@foreach($menu_relation_criterias as $menu_relation_criteria)
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = new {!! ucfirst(camel_case(str_singular($menu_relation_criteria->relation->table->name))) !!}();

@if($menu_relation_criteria->operator == 'like%')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->where('{!! $menu_relation_criteria->relation_field->name !!}', 'like', '%{!! $menu_relation_criteria->value !!}%');
@elseif($menu_relation_criteria->operator == 'like')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->where('{!! $menu_relation_criteria->relation_field->name !!}', 'like', '{!! $menu_relation_criteria->value !!}');
@elseif($menu_relation_criteria->operator == 'not_like')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->where('{!! $menu_relation_criteria->relation_field->name !!}', 'not like', '{!! $menu_relation_criteria->value !!}');
@elseif($menu_relation_criteria->operator == '=')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->where('{!! $menu_relation_criteria->relation_field->name !!}', '=', {!! $menu_relation_criteria->value !!});
@elseif($menu_relation_criteria->operator == '!=')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->where('{!! $menu_relation_criteria->relation_field->name !!}', '!=', {!! $menu_relation_criteria->value !!});
@elseif($menu_relation_criteria->operator == 'single_quotes=')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->where('{!! $menu_relation_criteria->relation_field->name !!}', '=', '{!! $menu_relation_criteria->value !!}');
@elseif($menu_relation_criteria->operator == '!single_quotes=')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->where('{!! $menu_relation_criteria->relation_field->name !!}', '!=', '{!! $menu_relation_criteria->value !!}');
@elseif($menu_relation_criteria->operator == 'in')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->whereIn('{!! $menu_relation_criteria->relation_field->name !!}', [
@foreach(explode(',', $menu_relation_criteria->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($menu_relation_criteria->operator == 'not_in')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->whereNotIn('{!! $menu_relation_criteria->relation_field->name !!}', [
@foreach(explode(',', $menu_relation_criteria->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($menu_relation_criteria->operator == 'between')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->whereBetween('{!! $menu_relation_criteria->relation_field->name !!}', [
@foreach(explode(',', $menu_relation_criteria->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($menu_relation_criteria->operator == 'not_between')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->whereNotBetween('{!! $menu_relation_criteria->relation_field->name !!}', [
@foreach(explode(',', $menu_relation_criteria->value) as $v)
            '{!! $v !!}',
@endforeach
        ]);
@elseif($menu_relation_criteria->operator == 'is_null')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->whereNull('{!! $menu_relation_criteria->relation_field->name !!}');
@elseif($menu_relation_criteria->operator == 'is_not_null')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->whereNotNull('{!! $menu_relation_criteria->relation_field->name !!}');
@elseif($menu_relation_criteria->operator == 'default')
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->where('{!! $menu_relation_criteria->relation_field->name !!}', '=', @if($menu_relation_criteria->value == 'current_user_id')Auth::id()@else{!! $menu_relation_criteria->value !!}@endif);
@else
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = ${!! str_singular($menu_relation_criteria->relation->table->name) !!}->where('{!! $menu_relation_criteria->relation_field->name !!}', {!! $menu_relation_criteria->operator !!}, {!! $menu_relation_criteria->value !!});
@endif
        ${!! str_singular($menu_relation_criteria->relation->table->name) !!} = QueryHelpers::getSingleData($request, ${!! str_singular($menu_relation_criteria->relation->table->name) !!});

        if(!empty(${!! str_singular($menu_relation_criteria->relation->table->name) !!})) {
            ${!! snake_case($menu->name) !!}->{!! !empty($menu_relation_criteria->relation->relation_name) ? $menu_relation_criteria->relation->relation_name : $menu_relation_criteria->relation->table->name !!}()->attach(${!! str_singular($menu_relation_criteria->relation->table->name) !!}->{!! $menu_relation_criteria->relation->local_key_field->name !!});
        }
@endforeach

@if(!empty($menu->table))
@foreach($menu->table->relations as $relation_index => $relation)
@if($relation->relation_type == "belongstomany")
        ${!! snake_case($menu->name) !!}->{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!}()->attach($request->{!! !empty($relation->relation_name) ? camel_case(str_plural($relation->relation_name)) : camel_case(str_plural($relation->table->name)) !!});
@endif
@endforeach
@endif
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
@php
$criteria = DB::table('menu_criteria')->where('menu_id', $menu->id)->where('field_id', $field->id)->first();
@endphp
@if((!empty($criteria) && $criteria->show_in_form) || empty($criteria))
@if ($field->ai || $field->input_type == "hidden")
@elseif($field->input_type == "password")
@else
            "{!! $field->name !!}" => "@php echo str_replace(
            ' ',
            '',
            \App\DefaultHelpers::render(\Illuminate\Support\Facades\Blade::compileString('
            @if($field->notnull)required @endif
            @if($field->index == "unique")|unique:{!! $menu->table->name !!}, {!! $field->name !!}, {$id} @endif
            @if($field->length > 0 && ($field->type == "tinyint" || $field->type == "smallint" || $field->type == "mediumint" || $field->type == "integer" || $field->type == "bigint" || $field->type == "float" || $field->type == "double" || $field->type == "decimal"))@if($field->notnull)|digits_between:1,{!! $field->length !!}@endif @endif
            @if($field->input_type == "email")|email @endif
            @if($field->input_type == "number")|numeric @endif
            @if($field->input_type == "url")|url @endif
            '),
            ['field' => $field, 'menu' => $menu])) @endphp",
@endif
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

        ${!! snake_case($menu->name) !!} = {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}::find($id);

@foreach($menu->table->fields as $field)
@php
$criteria = DB::table('menu_criteria')->where('menu_id', $menu->id)->where('field_id', $field->id)->first();
@endphp
@if((!empty($criteria) && $criteria->show_in_form) || empty($criteria))
@if ($field->ai)
@elseif($field->name == "updated_by")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = Auth::id();
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = Hash::make($request->{!! $field->name !!});
@elseif($field->input_type == "image")
        if (!empty($request->{!! $field->name !!}) && !strpos($request->{!! $field->name !!}, ${!! snake_case($menu->name) !!}->{!! $field->name !!})) {
            if (${!! snake_case($menu->name) !!}->{!! $field->name !!} !== null) {
                if (file_exists(${!! snake_case($menu->name) !!}->{!! $field->name !!})) {
                    unlink(public_path(${!! snake_case($menu->name) !!}->{!! $field->name !!}));
                }
            }

            preg_match("/^data:image\/(.*);base64/", $request->{!! $field->name !!}, $ext);

            $filename = time() . '.' . $ext[1];

            if (!is_dir('{!! snake_case($menu->name) !!}/{!! str_plural($field->name) !!}/')) mkdir('{!! snake_case($menu->name) !!}/{!! str_plural($field->name) !!}/', 0777, true);

            $path = public_path('/{!! snake_case($menu->name) !!}/{!! str_plural($field->name) !!}/' . $filename);
            Image::make($request->{!! $field->name !!})->save($path);
            ${!! snake_case($menu->name) !!}->{!! $field->name !!} = '{!! snake_case($menu->name) !!}/{!! str_plural($field->name) !!}/' . $filename;
        } elseif (empty($request->{!! $field->name !!})) {
            if (${!! snake_case($menu->name) !!}->{!! $field->name !!} !== null) {
                if (file_exists(${!! snake_case($menu->name) !!}->{!! $field->name !!})) {
                    unlink(public_path(${!! snake_case($menu->name) !!}->{!! $field->name !!}));
                }
            }

            ${!! snake_case($menu->name) !!}->{!! $field->name !!} = null;
        }
@elseif($field->type == "varchar")
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@else
        ${!! snake_case($menu->name) !!}->{!! $field->name !!} = $request->{!! $field->name !!};
@endif
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
        ${!! snake_case($menu->name) !!} = {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}::find($id);

        if (${!! snake_case($menu->name) !!} === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => '{!! title_case(str_replace('_', ' ', $menu->name)) !!} not found !',
            ], 400);

        }

        ${!! snake_case($menu->name) !!}->active_flag = false;
        ${!! snake_case($menu->name) !!}->save();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete {!! title_case(str_replace('_', ' ', $menu->name)) !!} !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        ${!! snake_case(str_plural($menu->name)) !!} = {!! ucfirst(camel_case(str_singular($menu->table->name))) !!}::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete {!! title_case(str_replace('_', ' ', str_plural($menu->name))) !!} !',
        ], 200);

    }
@endif
}
