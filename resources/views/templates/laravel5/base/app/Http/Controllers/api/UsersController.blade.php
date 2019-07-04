<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
@foreach($project->tables()->where('name', 'users')->first()->fields as $field_index => $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
use App\{!! ucfirst(camel_case(str_singular($field->relation->table->name))) !!};
@endif
@endif
@endforeach
use Illuminate\Http\Request;
use Image;
use Validator;
use Auth;
use Hash;

class UsersController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new User, ['user', 'roles']);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Users data !',
        ], 200);
    }

@foreach($project->tables()->where('name', 'users')->first()->fields as $field_index => $field)
@if(!empty($field->relation))
@if($field->relation->relation_type == "belongsto")
    public function get{!! ucfirst(camel_case(str_plural($field->relation->table->name))) !!}DataSet(Request $request)
    {
        $data = {!! ucfirst(camel_case(str_singular($field->relation->table->name))) !!}::select('{!! $field->relation->foreign_key_display_field->name !!}', '{!! $field->relation->foreign_key_field->name !!}')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get {!! title_case(str_replace('_', ' ', str_plural($field->relation->table->name))) !!} data !',
        ], 200);
    }
@endif
@endif
@endforeach

    public function getAllByKeyword($keyword)
    {
        $users = User::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Awesome, successfully get Users data !',
        ], 200);
    }

    public function getOne($id)
    {
        $users = User::find($id);

        // Data not found
        if ($users === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Awesome, successfully get Users data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|max:100",
            "email" => "required|unique:users,email|max:100|email",
            "address" => "",
            "password" => "required|max:100",
            "photo" => "",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new users !',
            ], 400);
        }

        $users = new User;

        $users->updated_by = Auth::id();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->address = $request->address;
        $users->password = Hash::make($request->password);

        if (!empty($request->photo)) {
            preg_match("/^data:image\/(.*);base64/", $request->photo, $ext);

            $filename = time() . '.' . $ext[1];
            $path = public_path('/users/photos/' . $filename);
            Image::make($request->photo)->save($path);
            $users->photo = 'users/photos/' . $filename;
        } else {
            $users->photo = null;
        }

        $users->save();

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Awesome, successfully create new Users !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|max:100",
            "email" => "required|email|unique:users,email,{$id}|max:100",
            "address" => "",
//            "password" => "required|max:100",
            "photo" => "",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update Users !',
            ], 400);
        }

        $users = User::find($id);

        $users->updated_by = Auth::id();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->address = $request->address;
        $users->password = Hash::make($request->password);

        if (!empty($request->photo) && !strpos($request->photo, $users->photo)) {
            if ($users->photo !== null) {
                if (file_exists($users->photo)) {
                    unlink(public_path($users->photo));
                }
            }

            preg_match("/^data:image\/(.*);base64/", $request->photo, $ext);

            $filename = time() . '.' . $ext[1];
            $path = public_path('/users/photos/' . $filename);
            Image::make($request->photo)->save($path);
            $users->photo = 'users/photos/' . $filename;
        } elseif (empty($request->photo)) {
            if ($users->photo !== null) {
                if (file_exists($users->photo)) {
                    unlink(public_path($users->photo));
                }
            }

            $users->photo = null;
        }

        $users->save();

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Awesome, successfully update Users !',
        ], 200);

    }

    public function destroy($id)
    {
        $users = User::find($id);

        if ($users === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Users not found !',
            ], 400);

        }

        $users->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete Users !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $users = User::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete Users !',
        ], 200);

    }

    public function addRole($user_id, $role_id)
    {
        // Validation

        $user = User::find($user_id);

        // Data not found
        if ($user === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        if (empty($user->roles()->where('id', $role_id)->first()))
            $user->roles()->attach($role_id);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Awesome, successfully add role !',
        ], 200);

    }

    public function deleteRole($user_id, $role_id)
    {
        // Validation

        $user = User::find($user_id);

        // Data not found
        if ($user === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        $user->roles()->detach($role_id);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Awesome, successfully delete role !',
        ], 200);

    }

    public function updateRolePermissions(Request $request, $role_id)
    {
        $role = Role::find($role_id);

        // Data not found
        if ($role === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        $role->permissions()->detach();
        foreach ($request->permissions as $permission) {
            $role->permissions()->attach($permission['id']);
        }

//        $role->permissions()->sync($permission_ids);

        return response()->json([
            'success' => true,
            'data' => $role,
            'message' => 'Awesome, successfully update permissions !',
        ], 200);

    }

    public function getPermissionsByRoleId($role_id)
    {
//        // Validation
//
//        $role = Role::find($role_id);
//
//        // Data not found
//        if (role === null) {
//            return response()->json([
//                'success' => false,
//                'data' => null,
//                'message' => 'Oops, Data not found !',
//            ], 400);
//        }
//
////        if (empty($user->roles()->where('id', $role_id)->first()))
////            $user->roles()->attach($role_id);
//
//        return response()->json([
//            'success' => true,
//            'data' => $user,
//            'message' => 'Awesome, successfully add role !',
//        ], 200);

    }
}
