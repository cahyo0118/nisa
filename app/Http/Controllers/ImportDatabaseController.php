<?php

namespace App\Http\Controllers;

use App\Field;
use App\Helpers\QueryHelpers;
use App\Project;
use App\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportDatabaseController extends Controller
{

    public function index(Request $request)
    {
        $databases = DB::select('SHOW DATABASES');
        $projects = Project::all();

        return view('import-db.index')->with('databases', $databases)->with('projects', $projects);
    }

    public function ajaxGetTablesByDB($db_name)
    {
        $tables = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='{$db_name}'");

        $view = (String)view('import-db.partials.table-item')->with('db_name', $db_name)->with('tables', $tables);

        return response()->json([
            'success' => true,
            'view' => $view,
            'data' => $tables,
            'message' => 'Generated'
        ], 200);
    }

    public function ajaxImportTables(Request $request, $project_id, $db_name)
    {

//        return response()->json([
//            'success' => true,
//            'data' => $request->json()->all(),
//            'message' => 'Generated'
//        ], 200);

        $request = $request->json()->all();

        $project = Project::find($project_id);

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

//        return response()->json([
//            'success' => true,
//            'data' => $request['tables'],
//            'message' => 'Generated'
//        ], 200);

        foreach ($request['tables'] as $import_table) {
//            $table = $project->tables()->where('name', $import_table['table_name'])->first();
//
//            if (empty($table)) {
//                $table = new Table();
//                $table->name = $import_table['table_name'];
//                $table->display_name = str_replace('_', ' ', $import_table['table_name']);
//                $table->project_id = $project_id;
//                $table->save();
//            }

//            return response()->json([
//                'success' => true,
//                'data' => $import_table['fields'],
//                'message' => 'Generated'
//            ], 200);

            foreach ($import_table['fields'] as $import_field) {

                $import_field = unserialize($import_field);

                return response()->json([
                    'success' => true,
                    'data' => QueryHelpers::getTextBetweenBracket($import_field->COLUMN_TYPE),
                    'message' => 'Generated'
                ], 200);

                $field = Field::where('name', $import_field)->first();

                if (empty($field)) {

                    $field = new Field();
                    $field->name = $import_field->COLUMN_NAME;
                    $field->display_name = title_case(str_replace('_', ' ', $import_field->COLUMN_NAME));
                    $field->type = $import_field->DATA_TYPE;
                    $field->input_type = "text";
                    $field->length = (int)QueryHelpers::getTextBetweenBracket($import_field->COLUMN_TYPE);
                    $field->index = 0;
                    $field->default = 0;
                    $field->notnull = false;
                    $field->unsigned = false;
                    $field->ai = false;
                    $field->searchable = false;
                    $field->table_id = $table->id;
                    $field->save();

                }
            }

        }


//        $tables = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='{$db_name}'");

//        $view = (String)view('import-db.partials.table-item')->with('db_name', $db_name)->with('tables', $tables);

        return response()->json(['success' => true,
//            'view' => $view,
            'data' => $request['tables'],
            'message' => 'Generated'], 200);
    }
}
