<?php

namespace App\Http\Controllers;

use App\Project;
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
}
