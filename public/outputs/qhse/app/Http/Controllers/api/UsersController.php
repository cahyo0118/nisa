<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;

class UsersController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new User);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Users data !',
        ], 200);
    }

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
        $users->photo = $request->photo;
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
            "email" => "required|unique:users,email,{$id}|max:100",
            "address" => "",
            "password" => "required|max:100",
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
        $users->photo = $request->photo;
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
}
