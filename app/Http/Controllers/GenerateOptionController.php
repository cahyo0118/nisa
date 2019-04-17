<?php

namespace App\Http\Controllers;

use App\GenerateOption;
use Illuminate\Http\Request;

class GenerateOptionController extends Controller
{
    public function index(Request $request)
    {
        $genarate_options = null;

        if (empty($request->keyword)) {
            $genarate_options = GenerateOption::paginate(15);
        } else {
            $genarate_options = GenerateOption::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        }

        return view('generate-options.index')->with('items', $genarate_options);
    }
}
