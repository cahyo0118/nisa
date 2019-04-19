<?php

namespace App\Http\Controllers;

use App\DefaultHelpers;
use App\Field;
use App\Menu;
use App\Relation;
use App\Table;
use Chumper\Zipper\Zipper;
use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
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

//        Optionals
        $project->db_connection = $request->db_connection;
        $project->db_host = $request->db_host;
        $project->db_port = $request->db_port;
        $project->db_name = $request->db_name;
        $project->db_username = $request->db_username;
        $project->db_password = $request->db_password;

        $project->mail_driver = $request->mail_driver;
        $project->mail_host = $request->mail_host;
        $project->mail_port = $request->mail_port;
        $project->mail_username = $request->mail_username;
        $project->mail_password = $request->mail_password;
        $project->mail_encryption = $request->mail_encryption;

        $project->item_per_page = $request->item_per_page;

        $project->save();

        /*Default table for new project*/
        $user_role_relation = new Relation();
        $role_permission_relation = new Relation();

//        users table
        $table_users = new Table();
        $table_users->name = "users";
        $table_users->display_name = "Users";
        $table_users->project_id = $project->id;
        $table_users->save();

        $user_role_relation->local_table_id = $table_users->id;

        $field = new Field();
        $field->name = "id";
        $field->display_name = "ID";
        $field->type = "integer";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = "primary";
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = true;
        $field->searchable = false;
        $field->table_id = $table_users->id;
        $field->save();

        $user_role_relation->relation_local_key = $field->id;

        $field = new Field();
        $field->name = "created_at";
        $field->display_name = "Created At";
        $field->type = "timestamp";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_users->id;
        $field->save();

        $field = new Field();
        $field->name = "updated_at";
        $field->display_name = "Updated At";
        $field->type = "timestamp";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_users->id;
        $field->save();

        $field = new Field();
        $field->name = "active_flag";
        $field->display_name = "Active Flag";
        $field->type = "boolean";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = "true";
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_users->id;
        $field->save();

        $field = new Field();
        $field->name = "updated_by";
        $field->display_name = "Updated By";
        $field->type = "integer";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_users->id;
        $field->save();

        $field = new Field();
        $field->name = "name";
        $field->display_name = "Name";
        $field->type = "varchar";
        $field->input_type = "text";
        $field->length = 100;
        $field->index = 0;
        $field->default = null;
        $field->notnull = true;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = true;
        $field->table_id = $table_users->id;
        $field->save();

        $field = new Field();
        $field->name = "email";
        $field->display_name = "Email";
        $field->type = "varchar";
        $field->input_type = "email";
        $field->length = 100;
        $field->index = "unique";
        $field->default = null;
        $field->notnull = true;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = true;
        $field->table_id = $table_users->id;
        $field->save();

        $field = new Field();
        $field->name = "address";
        $field->display_name = "Address";
        $field->type = "varchar";
        $field->input_type = "text";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_users->id;
        $field->save();

        $field = new Field();
        $field->name = "password";
        $field->display_name = "Password";
        $field->type = "varchar";
        $field->input_type = "password";
        $field->length = 100;
        $field->index = 0;
        $field->default = null;
        $field->notnull = true;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_users->id;
        $field->save();

        $field = new Field();
        $field->name = "photo";
        $field->display_name = "Photo";
        $field->type = "varchar";
        $field->input_type = "file";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_users->id;
        $field->save();

        $field = new Field();
        $field->name = "email_verified_at";
        $field->display_name = "Email Verified At";
        $field->type = "timestamp";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_users->id;
        $field->save();

        //        roles table
        $table_roles = new Table();
        $table_roles->name = "roles";
        $table_roles->display_name = "Roles";
        $table_roles->project_id = $project->id;
        $table_roles->save();

        $user_role_relation->table_id = $table_roles->id;
        $role_permission_relation->local_table_id = $table_roles->id;

        $field = new Field();
        $field->name = "id";
        $field->display_name = "ID";
        $field->type = "integer";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = "primary";
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = true;
        $field->searchable = false;
        $field->table_id = $table_roles->id;
        $field->save();

        $user_role_relation->relation_foreign_key = $field->id;
        $role_permission_relation->relation_local_key = $field->id;

        $field = new Field();
        $field->name = "created_at";
        $field->display_name = "Created At";
        $field->type = "timestamp";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_roles->id;
        $field->save();

        $field = new Field();
        $field->name = "updated_at";
        $field->display_name = "Updated At";
        $field->type = "timestamp";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_roles->id;
        $field->save();

        $field = new Field();
        $field->name = "active_flag";
        $field->display_name = "Active Flag";
        $field->type = "boolean";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = "true";
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_roles->id;
        $field->save();

        $field = new Field();
        $field->name = "updated_by";
        $field->display_name = "Updated By";
        $field->type = "integer";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_roles->id;
        $field->save();

        $field = new Field();
        $field->name = "name";
        $field->display_name = "Name";
        $field->type = "varchar";
        $field->input_type = "text";
        $field->length = 100;
        $field->index = "unique";
        $field->default = null;
        $field->notnull = true;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = true;
        $field->table_id = $table_roles->id;
        $field->save();

        $user_role_relation->relation_display = $field->id;

        $field = new Field();
        $field->name = "description";
        $field->display_name = "Description";
        $field->type = "varchar";
        $field->input_type = "textarea";
        $field->length = 100;
        $field->index = 0;
        $field->default = null;
        $field->notnull = true;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = true;
        $field->table_id = $table_roles->id;
        $field->save();

        //        permissions table
        $table_permissions = new Table();
        $table_permissions->name = "permissions";
        $table_permissions->display_name = "Permissions";
        $table_permissions->project_id = $project->id;
        $table_permissions->save();

        $role_permission_relation->table_id = $table_permissions->id;

        $field = new Field();
        $field->name = "id";
        $field->display_name = "ID";
        $field->type = "integer";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = "primary";
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = true;
        $field->searchable = false;
        $field->table_id = $table_permissions->id;
        $field->save();

        $role_permission_relation->relation_foreign_key = $field->id;

        $field = new Field();
        $field->name = "created_at";
        $field->display_name = "Created At";
        $field->type = "timestamp";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_permissions->id;
        $field->save();

        $field = new Field();
        $field->name = "updated_at";
        $field->display_name = "Updated At";
        $field->type = "timestamp";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_permissions->id;
        $field->save();

        $field = new Field();
        $field->name = "active_flag";
        $field->display_name = "Active Flag";
        $field->type = "boolean";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = "true";
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_permissions->id;
        $field->save();

        $field = new Field();
        $field->name = "updated_by";
        $field->display_name = "Updated By";
        $field->type = "integer";
        $field->input_type = "hidden";
        $field->length = 0;
        $field->index = 0;
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = false;
        $field->table_id = $table_permissions->id;
        $field->save();

        $field = new Field();
        $field->name = "name";
        $field->display_name = "Name";
        $field->type = "varchar";
        $field->input_type = "text";
        $field->length = 100;
        $field->index = "unique";
        $field->default = null;
        $field->notnull = false;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = true;
        $field->table_id = $table_permissions->id;
        $field->save();

        $role_permission_relation->relation_display = $field->id;

        $field = new Field();
        $field->name = "description";
        $field->display_name = "Description";
        $field->type = "varchar";
        $field->input_type = "textarea";
        $field->length = 100;
        $field->index = 0;
        $field->default = null;
        $field->notnull = true;
        $field->unsigned = false;
        $field->ai = false;
        $field->searchable = true;
        $field->table_id = $table_permissions->id;
        $field->save();

        $user_role_relation->field_id = null;
        $user_role_relation->relation_type = "belongstomany";

        $role_permission_relation->field_id = null;
        $role_permission_relation->relation_type = "belongstomany";

        $user_role_relation->save();
        $role_permission_relation->save();

        Session::flash('success', 'Successfully store data');

        return redirect()->route('projects.index');
    }

    public function ajaxGenerateProject(Request $request, $id, $template)
    {
        set_time_limit(0);
//        error_log(storage_path('template'));
        $zipper = new Zipper;

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

//            Storage::copy('templates/laravel5.zip', $project_directory . '/laravel5.zip');
//
//            $zipper->make($project_directory . '/laravel5.zip')->extractTo($project_directory);
//
//            Storage::delete($project_directory . '/laravel5.zip');
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
                                'project' => $project,
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

                    error_log("++++++++++++++++++++++++++++++++++++++");
                    error_log(100000 + $project->id + $table_index);
//                    error_log(floor(('%' . str_replace(':', '', date('h:i:s'))) . "1"));
//                    error_log(sprintf('%06d', str_replace(':', '', date('h:i:s')) . $table_index));
                }
            }

            //            Generate relation tables file
            foreach ($template_maps->files->relations as $relation_file) {

                foreach ($project->tables as $table_index => $table) {

                    foreach (Relation::where([
                        'local_table_id' => $table->id,
                        'relation_type' => 'belongstomany',
                    ])->get() as $relation_index => $relation) {

//                Create file and directory
                        if (!is_dir($project_directory . $relation_file->target_path))
                            mkdir($project_directory . $relation_file->target_path, 0777, true);

                        file_put_contents(
                            $project_directory . $relation_file->target_path . "/" . DefaultHelpers::render(
                                Blade::compileString($relation_file->target_filename), [
                                    'relation' => $relation,
                                    'project' => $project,
                                    'relation_index' => $relation_index,
                                    'table_index' => $table_index
                                ]
                            ),
                            (string)view(
                                $blade_directory
                                . str_replace("/", ".", $relation_file->resource_path) // change ordinary path to blade format
                                . ($relation_file->resource_path !== "/" ? "." : "") // if resource path is base path
                                . $relation_file->resource_filename // template file name
                            )->with('relation', $relation)->with('project', $project)->with('php_prefix', $php_prefix)

                        );

                    }
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

        //        Optionals
        $project->db_connection = $request->db_connection;
        $project->db_host = $request->db_host;
        $project->db_port = $request->db_port;
        $project->db_name = $request->db_name;
        $project->db_username = $request->db_username;
        $project->db_password = $request->db_password;

        $project->mail_driver = $request->mail_driver;
        $project->mail_host = $request->mail_host;
        $project->mail_port = $request->mail_port;
        $project->mail_username = $request->mail_username;
        $project->mail_password = $request->mail_password;
        $project->mail_encryption = $request->mail_encryption;

        $project->item_per_page = $request->item_per_page;

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

    public function ajaxDeleteProject($id)
    {
        $project = Project::find($id);

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed delete data'
            ], 400);
        }

        $project->delete();

        return response()->json([
            'success' => false,
            'message' => 'Successfully delete data'
        ], 200);
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
