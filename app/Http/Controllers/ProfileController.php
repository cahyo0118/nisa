<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserInfo;
use Auth;
use Session;
use Hash;
use Validator;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $info = $user->info;

        if (empty($info)) {
            $info = new UserInfo;
            $info->user_id = $user->id;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $info->organization = $request->organization ? $request->organization : NULL;
        $info->address = $request->address ? $request->address : NULL;
        $info->about = $request->about ? $request->about : NULL;
        $info->save();

        Session::flash('success', 'Successfully update data');

        return redirect()->back();
    }

    public function updateCurrentUserPassword(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|max:25',
            'new_password' => 'required|max:25',
        ]);

        $user = Auth::user();

        if (Hash::check($request->current_password, Auth::user()->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            Session::flash('success', 'Successfully update data');
            return redirect()->back();

        } else {

            Session::flash('failed', 'Oops, password does not match !');

            return redirect()->back();
        }

        Session::flash('failed', 'Oops, Internal server error !');
        return redirect()->back();

    }
}
