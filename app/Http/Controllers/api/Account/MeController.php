<?php

namespace App\Http\Controllers\api\Account;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MeController extends Controller
{
    public function getCurrentUser()
    {
        return response()->json([
            'success' => true,
            'body' => Auth::user(),
            'message' => 'Successfully get current user data'
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update current user',
            ], 400);
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'body' => $user,
            'message' => 'Successfully get current user data'
        ]);

    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password_old' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update current user',
            ], 400);
        }

        $user = Auth::user();

        if (
            Hash::check($request->password_old, $user->password)
            && $request->password == $request->password_confirmation
        ) {

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'body' => $user,
                'message' => 'Successfully get current user data'
            ]);

        } else {

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed update current user, password doesn\'t match',
            ], 400);

        }

    }
}
