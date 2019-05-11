<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;

class AdministratorController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new User);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Administrators data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        $administrators = User::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $administrators,
            'message' => 'Awesome, successfully get Administrators data !',
        ], 200);
    }

    public function getOne($id)
    {
        $administrator = User::find($id);

        // Data not found
        if ($administrator === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $administrator,
            'message' => 'Awesome, successfully get Administrator data !',
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
                'message' => 'Failed creating new administrator !',
            ], 400);
        }

        $administrator = new User;

        $administrator->updated_by = Auth::id();
        $administrator->name = $request->name;
        $administrator->email = $request->email;
        $administrator->address = $request->address;
        $administrator->password = Hash::make($request->password);
        $administrator->photo = $request->photo;
        $administrator->save();

        return response()->json([
            'success' => true,
            'data' => $administrator,
            'message' => 'Awesome, successfully create new Administrator !',
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
                'message' => 'Failed update Administrator !',
            ], 400);
        }

        $administrator = User::find($id);

        $administrator->updated_by = Auth::id();
        $administrator->name = $request->name;
        $administrator->email = $request->email;
        $administrator->address = $request->address;
        $administrator->password = Hash::make($request->password);
        $administrator->photo = $request->photo;
        $administrator->save();

        return response()->json([
            'success' => true,
            'data' => $administrator,
            'message' => 'Awesome, successfully update Administrator !',
        ], 200);

    }

    public function destroy($id)
    {
        $administrator = User::find($id);

        if ($administrator === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Administrator not found !',
            ], 400);

        }

        $administrator->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete Administrator !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $administrators = User::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete Administrators !',
        ], 200);

    }
}
