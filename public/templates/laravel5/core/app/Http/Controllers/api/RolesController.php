<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Image;
use Validator;
use Auth;
use Hash;

class RolesController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new Role);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Roles data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        $roles = Role::where('name', 'like', '%' . $keyword . '%')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $roles,
            'message' => 'Awesome, successfully get Roles data !',
        ], 200);
    }

    public function getOne($id)
    {
        $roles = Role::find($id);

        // Data not found
        if ($roles === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $roles,
            'message' => 'Awesome, successfully get Roles data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|max:100|unique:roles,name",
            "description" => "required|max:100",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new roles !',
            ], 400);
        }

        $roles = new Role;

        $roles->updated_by = Auth::id();
        $roles->name = $request->name;
        $roles->description = $request->description;

        $roles->save();

        return response()->json([
            'success' => true,
            'data' => $roles,
            'message' => 'Awesome, successfully create new Roles !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|max:100|unique:roles,name,{$id}",
            "description" => "required|max:100",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update Roles !',
            ], 400);
        }

        $roles = Role::find($id);

        $roles->updated_by = Auth::id();
        $roles->name = $request->name;
        $roles->description = $request->description;

        $roles->save();

        return response()->json([
            'success' => true,
            'data' => $roles,
            'message' => 'Awesome, successfully update Roles !',
        ], 200);

    }

    public function destroy($id)
    {
        $roles = Role::find($id);

        if ($roles === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Roles not found !',
            ], 400);

        }

        $roles->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete Roles !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $roles = Role::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete Roles !',
        ], 200);

    }

}
