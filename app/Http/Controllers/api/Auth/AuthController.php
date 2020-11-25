<?php

namespace App\Http\Controllers\api\Auth;

use App\ResetPasswordCode;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed to register',
            ], 400);
        }

        /*Check password*/
        if ($request->password != $request->password_confirmation) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed update current user, password doesn\'t match',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (empty($user)) {

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'body' => $user,
                'message' => 'Successfully register'
            ]);

        } else {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to register, email already taken',
            ], 400);
        }

    }

    public function requestResetPasswordCode(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed to reset password',
            ], 400);
        }

        if ($request->password != $request->password_confirmation) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed update current user, password doesn\'t match',
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (empty($user)) {

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to reset password, user not found',
            ], 400);


        } else {

            $reset_password_code = new ResetPasswordCode();
            $reset_password_code->email = $request->email;
            $reset_password_code->code = Str::random(10);
            $reset_password_code->active_until = Carbon::now()->addHour();
            $reset_password_code->save();

            return response()->json([
                'success' => true,
                'body' => null,
                'message' => 'Successfully generate reset password code'
            ]);

        }

    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed to reset password',
            ], 400);
        }

        $reset_password_code = ResetPasswordCode::where('code', $request->code)->first();

        if (empty($reset_password_code)) {

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to reset password, invalid code',
            ], 400);


        } else {

            /*Check password*/
            if ($request->password != $request->password_confirmation) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Failed update current user, password doesn\'t match',
                ], 400);
            }

            $user = User::where('email', $reset_password_code->email)->first();

            if (empty($user)) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Failed to reset password, invalid code',
                ], 400);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'body' => null,
                'message' => 'Successfully generate reset password code'
            ]);

        }

    }

}
