<?php

namespace App\Http\Controllers;

use App\Field;
use App\Project;
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
        'file' => 'File',
        'password' => 'Password',
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
        'year' => 'year',
        'enum' => 'enum',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tables = null;

        if (empty($request->keyword)) {
            $tables = Table::paginate(15);
        } else {
            $tables = Table::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        }

        return view('table.index')->with('items', $tables);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::pluck('display_name', 'id');

        return view('table.create')
            ->with('types', $this->types)
            ->with('input_types', $this->input_types)
            ->with('projects', $projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request);
        // Validation
        $validator = Validator::make($request->all(), Table::$validation['store']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
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
            $field->table_id = $table->id;
            $field->save();
        }

        Session::flash('success', 'Successfully store data');

        return redirect()->route('tables.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $table = Table::with('fields')->find($id);

        if (empty($table)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('table.show')->with('item', $table);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $table = Table::find($id);

        $projects = Project::pluck('display_name', 'id');

        if (empty($table)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

//        return response()->json([], 200);

        return view('table.edit')
            ->with('item', $table)
            ->with('types', $this->types)
            ->with('input_types', $this->input_types)
            ->with('projects', $projects);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), Table::$validation['update']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $table = Table::find($id);

        if (empty($table)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        $table->name = $request->table_name;
        $table->display_name = $request->table_display_name;
        $table->project_id = $request->table_project_id;
        $table->save();

        foreach ($request->name as $key => $field_name) {

            $field = new Field();

            if (!empty($request->id[$key])) {
                $field = Field::where('id', $request->id[$key])->first();
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
            $field->table_id = $table->id;
            $field->save();
        }

        Session::flash('success', 'Successfully update data');

        return redirect()->route('tables.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $table = Table::find($id);

        if (empty($table)) {
            Session::flash('failed', 'Failed delete data');
            return redirect()->route('tables.index');
        }

        $table->delete();

        Session::flash('success', 'Successfully delete data');
        return redirect()->route('tables.index');
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

    public function fields(Request $request, $id)
    {
        $value = "";

        $fields = Field::where('table_id', $id)->get();

        foreach ($fields as $field) {

            $random = rand(10000, 99999);

            $value .= (string)view('table.field-form')
                ->with('item', $field)
                ->with('random', $random)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types);
        }

        return response()->json([
            'view' => $value
        ], 200);
    }

    public function syncFields(Request $request, $id)
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
                ->with('item', $field)
                ->with('random', $random)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types);
        }

        return response()->json([
            'view' => $value
        ], 200);
    }

    public function addNewField()
    {
        $random = rand(10000, 99999);


        return response()->json([
            'view' => (string)view('table.field-form')
                ->with('random', $random)
                ->with('types', $this->types)
                ->with('input_types', $this->input_types)
        ], 200);
    }
}
