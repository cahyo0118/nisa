<?php

namespace App\Http\Controllers\api\Project;

use App\Field;
use App\Project;
use App\Relation;
use App\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GlobalVariableController extends Controller
{
    public function getAll()
    {
        $projects = Project::where('created_by', Auth::id())->get();

        return response()->json([
            'success' => true,
            'body' => $projects,
            'message' => 'Successfully get data'
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            Project::$validation['store']
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed add data',
            ], 400);
        }

        try {

            DB::beginTransaction();

            $project = new Project();
            $project->name = $request->name;
            $project->display_name = $request->display_name;

            /*Optional*/
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

            $project->created_by = Auth::id();

            $project->save();

            $this->initDefaultRelations($project->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'body' => $project,
                'message' => 'Successfully add data'
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed add data',
                'messageSystem' => $exception->getMessage(),
            ], 400);
        }

    }

    public function update(Request $request, $project_id)
    {

        $validator = Validator::make(
            $request->all(),
            Project::$validation['update']
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update data',
            ], 400);
        }

        try {

            DB::beginTransaction();

            $project = Project::where('id', $project_id)->first();

            if (empty($project)) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Failed update data, data not found',
                ], 400);
            }

            $project->name = $request->name;
            $project->display_name = $request->display_name;

            /*Optional*/
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

            $project->created_by = Auth::id();

            $project->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'body' => $project,
                'message' => 'Successfully update data'
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed update data',
                'messageSystem' => $exception->getMessage(),
            ], 400);
        }

    }

    public function delete($project_id)
    {
        $project = Project::where('id', $project_id)->first();

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed delete data, data not found',
            ], 400);
        }

        $project->delete();

        return response()->json([
            'success' => true,
            'body' => null,
            'message' => 'Successfully delete data'
        ]);
    }

    public function initDefaultRelations($project_id) {

        /*Default table for new project*/
        $user_role_relation = new Relation();
        $role_permission_relation = new Relation();

//        users table
        $table_users = new Table();
        $table_users->name = "users";
        $table_users->display_name = "Users";
        $table_users->project_id = $project_id;
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
        $field->type = "text";
        $field->input_type = "image";
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
        $table_roles->project_id = $project_id;
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
        $table_permissions->project_id = $project_id;
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

    }
}
