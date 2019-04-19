<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new User);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get users data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        $users = User::where('name', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->orWhere('username', 'like', '%' . $keyword . '%')
            ->paginate(5);

        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Awesome, successfully get users data !',
        ], 200);
    }

    public function getOne($id)
    {
        $user = User::find($id);

        // Data not found
        if ($user === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Awesome, successfully get user data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new user !',
            ], 400);
        }

        $user = new User;

        $user->title = $request->title;
        $user->subtitle = $request->subtitle;
        $user->start_date = "$request->start_date $request->start_time";
        $user->finish_date = "$request->finish_date $request->finish_time";
        $user->visibility_level = $request->visibility_level;
        $user->vote_type = $request->vote_type;
        $user->is_publish = $request->is_publish;

        $user->save();

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Awesome, successfully create new user !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update user !',
            ], 400);
        }

        $user = User::find($id);

        $user->title = $request->title;
        $user->subtitle = $request->subtitle;
        $user->start_date = "$request->start_date $request->start_time";
        $user->finish_date = "$request->finish_date $request->finish_time";
        $user->visibility_level = $request->visibility_level;
        $user->vote_type = $request->vote_type;
        $user->is_publish = $request->is_publish;

        $user->save();

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Awesome, successfully update user !',
        ], 200);

    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'User not found !',
            ], 400);

        }

        $user->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete user !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $users = User::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete users !',
        ], 200);

    }
}
