<?php

namespace App\Http\Controllers\api\GenerateOption;

use App\GenerateOption;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class GenerateOptionController extends Controller
{
    public function getAll()
    {
        $genarate_options = [];

        if (empty($request->keyword)) {
            $genarate_options = GenerateOption::paginate(15);
        } else {
            $genarate_options = GenerateOption::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        }

        return response()->json([
            'success' => true,
            'body' => $genarate_options,
            'message' => 'Successfully get data'
        ]);
    }
}
