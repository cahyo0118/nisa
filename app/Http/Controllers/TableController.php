<?php

namespace App\Http\Controllers;

use App\Field;
use App\Project;
use App\Relation;
use App\StaticDataset;
use Illuminate\Http\Request;
use App\Table;
use Validator;
use Session;

class TableController extends Controller
{
    private $input_types = [
        'text' => 'Text',
        'textarea' => 'Text Area',
        'email' => 'Email',
        'number' => 'Number',
        'checkbox' => 'Checkbox',
        'file' => 'File',
        'password' => 'Password',
        'select' => 'Select',
        'date' => 'Date',
        'time' => 'Time',
        'datetime' => 'Date Time',
        'hidden' => 'Hidden',
        'radio' => 'Radio',
        'range' => 'Range',
        'url' => 'URL',
        'image' => 'Image',
    ];

    private $types = [
        'varchar' => 'varchar',
        'tinyint' => 'tinyint',
        'smallint' => 'smallint',
        'mediumint' => 'mediumint',
        'integer' => 'integer',
        'bigint' => 'bigint',
        'float' => 'float',
        'double' => 'double',
        'text' => 'text',
        'decimal' => 'decimal',
        'char' => 'char',
        'date' => 'date',
        'datetime' => 'datetime',
        'timestamp' => 'timestamp',
        'time' => 'time',
//        'year' => 'year',
        'enum' => 'enum',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $project_id)
    {
        $tables = null;

        if (empty($request->keyword)) {
            $tables = Table::paginate(15);
        } else {
            $tables = Table::where('name', 'LIKE', '%' . $request->keyword . '%')
                ->where('project_id', $project_id)
                ->paginate(15);
        }

        return view('table.index')
            ->with('project_id', $project_id)
            ->with('items', $tables);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $projects = Project::pluck('display_name', 'id');

        return view('table.create')
            ->with('project_id', $project_id)
            ->with('types', $this->types)
            ->with('input_types', $this->input_types)
            ->with('id', $project_id)
            ->with('projects', $projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        // Validation
        $validator = Validator::make($request->all(), Table::$validation['store']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $table = Table::where('name', $request->table_name)->where('project_id', $project_id)->first();

        if (!empty($table)) {

            Session::flash('failed', 'Table name already taken');

            return redirect()
                ->back()
                ->withInput();
        }

        $table = new Table();
        $table->name = $request->table_name;
        $table->display_name = $request->table_display_name;
        $table->project_id = $request->table_project_id;
        $table->save();

        foreach ($request->name as $key => $field_name) {
            $field = new Field();
            $field->name = $request->name[$key];
            $field->display_name = $request->display_name[$key];
            $field->type = $request->type[$key];
            $field->input_type = $request->input_type[$key];
            $field->length = $request->length[$key];
            $field->index = $request->index[$key];
            $field->default = $request->default[$key];
            $field->notnull = $request->notnull[$key];
            $field->unsigned = $request->unsigned[$key];
            $field->ai = $request->ai[$key];
            $field->searchable = $request->searchable[$key];
            $field->table_id = $table->id;
            $field->save();

//            Relation
            if (!empty($request->relation_type[$key])) {
                $relation = Relation::where('field_id', $field->id)->first();

                if (empty($relation)) {
                    $relation = new Relation();
                }

                $relation->relation_name = $request->relation_name[$key];
                $relation->relation_display_name = $request->relation_display_name[$key];

                $relation->field_id = $field->id;
                $relation->table_id = $request->relation_table[$key];
                $relation->local_table_id = $table->id;
                $relation->relation_type = $request->relation_type[$key];
                $relation->relation_foreign_key = $request->relation_foreign_key[$key];
                $relation->relation_display = $request->relation_display[$key];

                $relation->save();
            }
        }

        if (!empty($request->relation_type)) {

//        Has Many or Many to Many relation
            foreach ($request->relation_type as $key => $field_name) {

                if ($request->relation_type[$key] == 'hasmany' || $request->relation_type[$key] == 'manytomany') {

                    $relation = Relation::where('field_id', $field->id)->first();

                    if (empty($relation)) {
                        $relation = new Relation();
                    }

                    $relation->relation_name = $request->relation_name[$key];
                    $relation->relation_display_name = $request->relation_display_name[$key];

                    $relation->field_id = 0;
                    $relation->table_id = $request->relation_table[$key];
                    $relation->local_table_id = $table->id;
                    $relation->relation_type = $request->relation_type[$key];
                    $relation->relation_foreign_key = $request->relation_foreign_key[$key];
                    $relation->relation_display = $request->relation_display[$key];

                    $relation->save();
                }
            }

        }

        Session::flash('success', 'Successfully store data');

        return redirect()->route('projects.tables', ['id' => $table->project_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $id)
    {
        $table = Table::with('fields')->find($id);

        if (empty($table)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('table.show')
            ->with('project_id', $project_id)
            ->with('item', $table);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($project_id, $id)
    {
        $table = Table::find($id);

        $projects = Project::pluck('display_name', 'id');

        if (empty($table)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('table.edit')
            ->with('item', $table)
            ->with('types', $this->types)
            ->with('input_types', $this->input_types)
            ->with('project_id', $project_id)
            ->with('projects', $projects);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $id)
    {
//        dd($request);
        // Validation
        $validator = Validator::make($request->all(), Table::$validation['update']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $table = Table::find($id);

        $field_order = $table->fields()->max('order');

        if (empty($table)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        try {

            $table->name = $request->table_name;
            $table->display_name = $request->table_display_name;
            $table->project_id = $request->table_project_id;
            $table->save();

//        Fields
            foreach ($request->name as $key => $field_name) {

                $field = new Field();

                if (!empty($request->id[$key])) {
                    $field = Field::where('id', $request->id[$key])->first();
                } else {
                    $field_order++;
                    $field->order = $field_order;
                }

                $field->name = $request->name[$key];
                $field->display_name = $request->display_name[$key];
                $field->type = $request->type[$key];
                $field->input_type = $request->input_type[$key];
                $field->length = $request->length[$key];
                $field->index = $request->index[$key];
                $field->default = $request->default[$key];
                $field->notnull = $request->notnull[$key];
                $field->unsigned = $request->unsigned[$key];
                $field->ai = $request->ai[$key];
                $field->searchable = $request->searchable[$key];
                $field->table_id = $table->id;
                if (!empty($request->dataset_type[$key])) {
                    $field->dataset_type = $request->dataset_type[$key];
                }
                $field->save();

//            Relation
                if (!empty($request->relation_type[$key])) {
                    $relation = Relation::where('field_id', $field->id)->first();

                    if (empty($relation)) {
                        $relation = new Relation();
                    }

                    $relation->relation_name = $request->relation_name[$key];
                    $relation->relation_display_name = $request->relation_display_name[$key];

                    $relation->field_id = $field->id;
                    $relation->table_id = $request->relation_table[$key];
                    $relation->local_table_id = $table->id;
                    $relation->relation_type = $request->relation_type[$key];
                    $relation->relation_foreign_key = $request->relation_foreign_key[$key];
                    $relation->relation_display = $request->relation_display[$key];

                    $relation->save();
                }
            }

            if (!empty($request->relation_type)) {

//            Has Many or Many to Many relation
                foreach ($request->relation_type as $key => $field_name) {

                    if ($request->relation_type[$key] == 'hasmany' || $request->relation_type[$key] == 'belongstomany') {

//                $relation = Relation::where('field_id', $field->id)->first();
                        $relation = Relation::where('id', $request->relation_id[$key])->first();

                        if (empty($relation)) {
                            $relation = new Relation();
                        }

                        $relation->relation_name = $request->relation_name[$key];
                        $relation->relation_display_name = $request->relation_display_name[$key];

                        $relation->field_id = null;
                        $relation->table_id = $request->relation_table[$key];
                        $relation->local_table_id = $table->id;
                        $relation->relation_type = $request->relation_type[$key];
                        $relation->relation_foreign_key = $request->relation_foreign_key[$key];
                        $relation->relation_local_key = !empty($request->relation_local_key[$key]) ? $request->relation_local_key[$key] : null;
                        $relation->relation_display = $request->relation_display[$key];

                        $relation->save();
                    }
                }

            }

        } catch (\Exception $e) {
            Session::flash('failed', $e->getMessage());
            return redirect()->back();
        }

        Session::flash('success', 'Successfully update data');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $id)
    {
        $table = Table::find($id);

        if (empty($table)) {
            Session::flash('failed', 'Failed delete data');
            return redirect()->back();
        }

        $table->delete();

        Session::flash('success', 'Successfully delete data');
        return redirect()->back();
    }

    public function ajaxDeleteTable($id)
    {
        $table = Table::find($id);

        if (empty($table)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed delete data'
            ], 400);
        }

        $table->delete();

        return response()->json([
            'success' => false,
            'message' => 'Successfully delete data'
        ], 200);
    }

    public function destroyField($field_id)
    {
        $field = Field::find($field_id);

        if (empty($field)) {
            return response()->json([
                'message' => 'Field not found !',
                'body' => null,
                'success' => false
            ], 400);
        }

        $field->delete();

        return response()->json([
            'message' => 'Field deleted !',
            'body' => null,
            'success' => true
        ], 200);
    }

    public function search(Request $request)
    {
        if (empty($request->keyword)) {
            return redirect()->route('tables.index');
        }

        $tables = Table::where('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        return view('table.index')->with('items', $tables);
    }

    public function fields(Request $request, $project_id, $id)
    {
        $value = "";
        $randoms = [];
        $field_ids = [];

        $fields = Field::where('table_id', $id)->orderBy('order')->get();

        foreach ($fields as $field) {

            $random = rand(10000, 99999);

            array_push($randoms, $random);
            array_push($field_ids, $field->id);

            $value .= (string)view('table.field-form')
                ->with('project_id', $project_id)
                ->with('item', $field)
                ->with('random', $random)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types);
        }

        return response()->json([
            'random' => $randoms,
            'field_ids' => $field_ids,
            'data' => $fields,
            'view' => $value
        ], 200);
    }

    public function relationsMany(Request $request, $project_id, $id)
    {
        $value = "";
        $randoms = [];
        $relation_tables = [];
        $relation_ids = [];
        $field_ids = [];
        $field_display_ids = [];

        $tables = Table::pluck('name', 'id');

        $relations = Relation::where('local_table_id', $id)
            ->where('relation_type', 'hasmany')
            ->orWhere('local_table_id', $id)
            ->where('relation_type', 'belongstomany')
            ->get();

        foreach ($relations as $relation) {

            $random = rand(10000, 99999);

            array_push($randoms, $random);
            array_push($relation_ids, $relation->id);
            array_push($field_ids, $relation->relation_foreign_key);
            array_push($field_display_ids, $relation->relation_display);
            array_push($relation_tables, $relation->table_id);

            if ($relation->relation_type == 'hasmany' || $relation->relation_type == 'belongstomany') {
                $value .= (string)view(($relation->relation_type == 'hasmany') ? 'table.field-relation-hm-form' : 'table.field-relation-mtm-form')
                    ->with('project_id', $project_id)
                    ->with('item', $relation)
                    ->with('tables', $tables)
                    ->with('random', $random)
                    ->with('types', $this->types)
                    ->with('input_types', $this->input_types);
            }
        }

        return response()->json([
            'random' => $randoms,
            'relation_ids' => $relation_ids,
            'field_display_ids' => $field_display_ids,
            'field_ids' => $field_ids,
            'relation_tables' => $relation_tables,
            'view' => $value
        ], 200);
    }

    public function syncFields(Request $request, $project_id, $id)
    {
//        $data = urldecode($request->table_name);
//        return $data;
//        foreach ($request->all() as $value) {
//            print_r($value);
//        }
        return $request;
//        $value = "";
//
//        $table = Table::find($id);
//
//        if (empty($table)) {
//            return response()->json([
//                'success' => false,
//                'data' => null,
//                'message' => 'Table not found!'
//            ], 400);
//        }

//        return response()->json([
//            'ddd' => $request
//        ], 200);

//        dd($request->gokreg);
//        foreach ($request->name as $key => $field_name) {
//            $field = new Field();
//            $field->name = $request->name[$key];
//            $field->display_name = $request->display_name[$key];
//            $field->type = $request->type[$key];
//            $field->input_type = $request->input_type[$key];
//            $field->length = $request->length[$key];
//            $field->index = $request->index[$key];
//            $field->default = $request->default[$key];
//            $field->notnull = $request->notnull[$key];
//            $field->unsigned = $request->unsigned[$key];
//            $field->ai = $request->ai[$key];
//            $field->table_id = $table->id;
//            $field->save();
//        }

        $fields = Field::where('table_id', $id)->get();

        foreach ($fields as $field) {

            $random = rand(10000, 99999);

            $value .= (string)view('table.field-form')
                ->with('project_id', $project_id)
                ->with('item', $field)
                ->with('random', $random)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types);
        }

        return response()->json([
            'view' => $value
        ], 200);
    }

    /*Templates*/

    public function fieldRelationTemplate(Request $request, $project_id, $id, $random)
    {
        $tables = Table::pluck('name', 'id');

        $item = Relation::where('field_id', $id)->first();

        if (empty($item)) {
            return response()->json([
                'success' => false,
                'view' => null,
                'body' => null,
                'message' => 'Relation not found'
            ], 204);
        }

        return response()->json([
            'view' => (string)view('table.field-relation-form')
                ->with('random', $random)
                ->with('project_id', $project_id)
                ->with('item', $item)
                ->with('tables', $tables)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types)
        ], 200);
    }

    public function fieldDatasetTemplate(Request $request, $project_id, $id, $random)
    {
        $item = Field::find($id);

        $static_datasets = StaticDataset::where('field_id', $id)->get();

        if (empty($static_datasets)) {
            return response()->json([
                'success' => false,
                'body' => null,
                'message' => 'Dataset not found'
            ], 204);
        }

        return response()->json([
            'view' => (string)view('table.field-static-dataset')
                ->with('project_id', $project_id)
                ->with('item', $item)
                ->with('static_datasets', $static_datasets)
                ->with('random', $random)
        ], 200);
    }

    public function addNewField($project_id)
    {
        $random = rand(10000, 99999);


        return response()->json([
            'view' => (string)view('table.field-form')
                ->with('project_id', $project_id)
                ->with('random', $random)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types)
        ], 200);
    }

//    Has Many
    public function addNewHasManyRelation($project_id)
    {
        $random = rand(10000, 99999);

        $tables = Table::where('project_id', $project_id)->pluck('name', 'id');

        return response()->json([
            'random' => $random,
            'view' => (string)view('table.field-relation-hm-form')
                ->with('random', $random)
                ->with('tables', $tables)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types)
        ], 200);
    }

//    Many to many
    public function addNewManyToManyRelation($project_id)
    {
        $random = rand(10000, 99999);

        $tables = Table::where('project_id', $project_id)->pluck('name', 'id');

        return response()->json([
            'random' => $random,
            'view' => (string)view('table.field-relation-mtm-form')
                ->with('random', $random)
                ->with('tables', $tables)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types)
        ], 200);
    }

    public function addNewRelation(Request $request, $project_id, $id, $random)
    {
//        $field = Field::find($id);
//
//        if (empty($field)) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Data not found !'
//            ], 400);
//        }

        $tables = Table::where('project_id', $project_id)->pluck('name', 'id');
//        $projects = Project::pluck('display_name', 'id');

        $item = Field::find($id);

        return response()->json([
            'view' => (string)view('table.field-relation-form')
                ->with('project_id', $project_id)
                ->with('item', $item)
                ->with('random', $random)
                ->with('tables', $tables)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types)
        ], 200);
    }

    public function addNewDataset(Request $request, $project_id, $id, $random)
    {

        $tables = Table::where('project_id', $project_id)->pluck('name', 'id');

        $item = Field::find($id);

        $static_datasets = StaticDataset::where('field_id', $id)->get();

        return response()->json([
            'view' => (string)view('table.field-static-dataset')
                ->with('project_id', $project_id)
                ->with('item', $item)
                ->with('static_datasets', $static_datasets)
                ->with('random', $random)
        ], 200);
    }

    public function ajaxStoreNewDataset(Request $request, $project_id, $field_id)
    {
        $request = $request->json()->all();

        $static_dataset = StaticDataset::where('value', $request['value'])
            ->where('field_id', $field_id)
            ->first();

        if (!empty($static_dataset)) {
            return response()->json([
                'success' => false,
                'message' => 'Dataset value already taken'
            ], 400);
        }

        $static_dataset = new StaticDataset();
        $static_dataset->value = $request['value'];
        $static_dataset->label = $request['label'];
        $static_dataset->field_id = $field_id;
        $static_dataset->save();

        return response()->json([
            'success' => true,
            'view' => (string)view('table.partials.static-dataset-item')->with('static_dataset', $static_dataset),
            'data' => $static_dataset,
            'message' => 'Successfully add new dataset !'
        ], 200);

    }

    public function ajaxUpdateDataset(Request $request, $id)
    {
        $request = $request->json()->all();

        $static_dataset = StaticDataset::find($id);

        if (empty($static_dataset)) {
            return response()->json([
                'success' => false,
                'message' => 'Dataset not found'
            ], 400);
        }

        $static_dataset->value = $request['value'];
        $static_dataset->label = $request['label'];
        $static_dataset->save();

        return response()->json([
            'success' => true,
            'view' => (string)view('table.partials.static-dataset-item')->with('static_dataset', $static_dataset),
            'data' => $static_dataset,
            'message' => 'Successfully update dataset !'
        ], 200);

    }

    public function ajaxFieldMoveUp(Request $request, $id)
    {
        $request = $request->json()->all();

        $field = Field::find($id);

        if (empty($field)) {
            return response()->json([
                'success' => false,
                'message' => 'Field not found'
            ], 400);
        }

        $fields = $field->table->fields()->orderBy('order')->get();

        foreach ($fields as $f_index => $f) {
            if ($f->id == $field->id) {
                $temp_order = $field->order;

                $target_order = $fields[$f_index - 1]->order;
                error_log("--- " . $field->order . " -> " . $target_order);

                $field->order = $target_order;
                $field->save();

                error_log("--- " . $fields[$f_index - 1]->order . " -> " . $temp_order);
                $fields[$f_index - 1]->order = $temp_order;
                $fields[$f_index - 1]->save();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $field,
            'message' => 'Successfully update dataset !'
        ], 200);

    }

    public function ajaxFieldMoveDown(Request $request, $id)
    {
        $request = $request->json()->all();

        $field = Field::find($id);

        if (empty($field)) {
            return response()->json([
                'success' => false,
                'message' => 'Field not found'
            ], 400);
        }

        $fields = $field->table->fields()->orderBy('order')->get();

        foreach ($fields as $f_index => $f) {
            if ($f->id == $field->id) {
                $temp_order = $field->order;

                $target_order = $fields[$f_index + 1]->order;
                error_log("--- " . $field->order . " -> " . $target_order);

                $field->order = $target_order;
                $field->save();

                error_log("--- " . $fields[$f_index + 1]->order . " -> " . $temp_order);
                $fields[$f_index + 1]->order = $temp_order;
                $fields[$f_index + 1]->save();
            }
        }

        return response()->json([
            'success' => true,
            'data' => $field,
            'message' => 'Successfully update dataset !'
        ], 200);

    }

    public function ajaxDeleteDataset($id)
    {
        $static_dataset = StaticDataset::find($id);

        if (empty($static_dataset)) {
            return response()->json([
                'success' => false,
                'message' => 'Dataset not found'
            ], 400);
        }

        $static_dataset->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully delete dataset !'
        ], 200);

    }

    public function deleteFieldRelation($id)
    {
//        $relation =
        return response()->json([
            'success' => true,
            'body' => null,
            'message' => 'Field relation removed !'
        ], 200);
    }

    public function getAllTables($random)
    {
        $tables = Table::pluck('name', 'id');
//        $projects = Project::pluck('name', 'id');

        return response()->json([
            'view' => (string)view('table.field-relation-form')
                ->with('random', $random)
                ->with('tables', $tables)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types)
        ], 200);
    }

    public function getAllFieldsSelectForm($random, $table_id)
    {
        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');
        $all_fields = Field::where('table_id', $table_id)->get();

        return response()->json([
            'view' => (string)view('table.select.foreigns')
                ->with('random', $random)
                ->with('fields', $fields),
            'view_criteria' => (string)view('table.partials.relation-criterias')
                ->with('random', $random)
                ->with('fields', $all_fields)
        ], 200);
    }

    public function getAllFieldsSelectFormByFieldId($random, $table_id, $field_id)
    {

        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');

        $item = Relation::where('field_id', $field_id)->first();

        return response()->json([
            'view' => (string)view('table.select.foreigns')
                ->with('item', $item)
                ->with('random', $random)
                ->with('fields', $fields)
        ], 200);
    }

    public function getAllDisplaysSelectForm($random, $table_id)
    {
        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');

        return response()->json([
            'view' => (string)view('table.select.displays')
                ->with('random', $random)
                ->with('fields', $fields)
        ], 200);
    }

    public function getAllDisplaysSelectFormByFieldId($random, $table_id, $field_id)
    {
        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');

        $item = Relation::where('field_id', $field_id)->first();

        return response()->json([
            'view' => (string)view('table.select.displays')
                ->with('item', $item)
                ->with('random', $random)
                ->with('fields', $fields)
        ], 200);
    }

    /* has many and many to many */
    public function getAllManyFieldsSelectForm($random, $table_id)
    {
        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');

        return response()->json([
            'view' => (string)view('table.select.foreigns')
                ->with('random', $random)
                ->with('fields', $fields)
        ], 200);
    }

    public function getAllManyFieldsSelectFormLocal($random, $table_id)
    {
        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');

        return response()->json([
            'view' => (string)view('table.select.locals')
                ->with('random', $random)
                ->with('fields', $fields)
        ], 200);
    }

    public function getAllManyFieldsSelectFormByFieldId($random, $table_id, $field_id)
    {

        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');

        $item = Relation::where('table_id', $table_id)->where('relation_foreign_key', $field_id)->first();

        return response()->json([
            'view' => (string)view('table.select.foreigns')
                ->with('item', $item)
                ->with('random', $random)
                ->with('fields', $fields)
        ], 200);
    }

    public function getAllManyFieldsSelectFormByFieldIdLocal($random, $table_id, $field_id)
    {

        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');

        $item = Relation::where('table_id', $table_id)->where('relation_foreign_key', $field_id)->first();

        return response()->json([
            'view' => (string)view('table.select.locals')
                ->with('item', $item)
                ->with('random', $random)
                ->with('fields', $fields)
        ], 200);
    }

    public function getAllManyDisplaysSelectForm($random, $table_id)
    {
        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');

        return response()->json([
            'view' => (string)view('table.select.displays')
                ->with('random', $random)
                ->with('fields', $fields)
        ], 200);
    }

    public function getAllManyDisplaysSelectFormByFieldId($random, $table_id, $field_id)
    {
        $fields = Field::where('table_id', $table_id)->pluck('name', 'id');

        $item = Relation::where('table_id', $table_id)->where('relation_display', $field_id)->first();

        error_log($table_id);
        error_log($fields);

        return response()->json([
            'view' => (string)view('table.select.displays')
                ->with('item', $item)
                ->with('random', $random)
                ->with('fields', $fields)
        ], 200);
    }

    public function deleteManyRelation($id)
    {

        $item = Relation::where('id', $id)->first();

        $item->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Successfully delete data !'
        ], 200);
    }

    /* END has many and many to many */

    public function implementor()
    {
        $tables = Table::all();

        foreach ($tables as $table) {
            foreach ($table->fields()->orderBy('id')->get() as $field_index => $field) {
                $field->order = $field_index + 1;
                $field->save();
            }
        }
    }
}
