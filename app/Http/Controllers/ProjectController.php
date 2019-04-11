<?php

namespace App\Http\Controllers;

use App\DefaultHelpers;
use App\Menu;
use App\Table;
use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Validator;
use Session;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = null;

        if (empty($request->keyword)) {
            $projects = Project::paginate(15);
        } else {
            $projects = Project::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        }

        return view('project.index')->with('items', $projects);
    }

    public function menus(Request $request, $project_id)
    {
        return view('menu.index')->with('id', $project_id);
    }

    public function tables(Request $request, $project_id)
    {

        $tables = null;

        if (empty($request->keyword)) {
            $tables = Table::where('project_id', $project_id)->paginate(15);
        } else {
            $tables = Table::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->where('project_id', $project_id)->paginate(15);
        }

        return view('table.index')->with('items', $tables)->with('id', $project_id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), Project::$validation['store']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = new Project();
        $project->name = $request->name;
        $project->display_name = $request->display_name;
        $project->save();

        Session::flash('success', 'Successfully store data');

        return redirect()->route('projects.index');
    }

    public function ajaxGenerateProject(Request $request, $id, $template)
    {

        $php_prefix = "<?php";

        $project = Project::find($id);

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        try {
//            Creating Folder
            $blade_directory = "templates.laravel5.base";
            $template_directory = "resources/views/templates/laravel5";
            $project_directory = "outputs/$project->name";
            if (!is_dir($project_directory)) mkdir($project_directory, 0777, true);

//            Read templates map
            $template_maps = json_decode(file_get_contents(base_path($template_directory . "/templates.json")));

//            return response()->json($template_maps->files);

//            Generate cores file
            foreach ($template_maps->files->core as $core_file) {

//                Create file and directory
                if (!is_dir($project_directory . $core_file->target_path))
                    mkdir($project_directory . $core_file->target_path, 0777, true);

                file_put_contents(
                    $project_directory . $core_file->target_path . "/" . $core_file->target_filename,
                    (string)view(
                        $blade_directory
                        . str_replace("/", ".", $core_file->resource_path) // change ordinary path to blade format
                        . ($core_file->resource_path !== "/" ? "." : "") // if resource path is base path
                        . $core_file->resource_filename // template file name
                    )->with('project', $project)->with('php_prefix', $php_prefix)

                );
            }

//            Generate menus file
            foreach ($template_maps->files->menus as $menu_file) {

                foreach ($project->menus as $menu) {

//                Create file and directory
                    if (!is_dir($project_directory . $menu_file->target_path))
                        mkdir($project_directory . $menu_file->target_path, 0777, true);

                    file_put_contents(
                        $project_directory
                        . $menu_file->target_path
                        . "/"
                        . DefaultHelpers::render(Blade::compileString($menu_file->target_filename), ['menu' => $menu]),
                        (string)view(
                            $blade_directory
                            . str_replace("/", ".", $menu_file->resource_path) // change ordinary path to blade format
                            . ($menu_file->resource_path !== "/" ? "." : "") // if resource path is base path
                            . $menu_file->resource_filename // template file name
                        )->with('menu', $menu)
                            ->with('project', $project)
                            ->with('php_prefix', $php_prefix)

                    );

                }
            }

            //            Generate tables file
            foreach ($template_maps->files->tables as $table_file) {

                foreach ($project->tables as $table_index => $table) {

//                Create file and directory
                    if (!is_dir($project_directory . $table_file->target_path))
                        mkdir($project_directory . $table_file->target_path, 0777, true);

                    file_put_contents(
                        $project_directory . $table_file->target_path . "/" . DefaultHelpers::render(
                            Blade::compileString($table_file->target_filename), [
                                'table' => $table,
                                'table_index' => $table_index
                            ]
                        ),
                        (string)view(
                            $blade_directory
                            . str_replace("/", ".", $table_file->resource_path) // change ordinary path to blade format
                            . ($table_file->resource_path !== "/" ? "." : "") // if resource path is base path
                            . $table_file->resource_filename // template file name
                        )->with('table', $table)->with('project', $project)->with('php_prefix', $php_prefix)

                    );

                }
            }


        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Generated'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);

        if (empty($project)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('project.show')->with('item', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);

        if (empty($project)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('project.edit')->with('item', $project);
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
        $validator = Validator::make($request->all(), Project::$validation['update']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = Project::find($id);

        if (empty($project)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        $project->name = $request->name;
        $project->display_name = $request->display_name;
        $project->save();

        Session::flash('success', 'Successfully update data');

        return redirect()->route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (empty($project)) {
            Session::flash('failed', 'Failed delete data');
            return redirect()->route('projects.index');
        }

        $project->delete();

        Session::flash('success', 'Successfully delete data');
        return redirect()->route('projects.index');
    }

    public function search(Request $request)
    {
        if (empty($request->keyword)) {
            return redirect()->route('projects.index');
        }

        $projects = Project::where('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        return view('project.index')->with('items', $projects);
    }
}
