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

    public function ajaxStore(Request $request)
    {
        $request = $request->json()->all();

        $generate_option = GenerateOption::where('name', $request['name'])->first();

        if (!empty($generate_option)) {
            return response()->json([
                'success' => false,
                'message' => 'Options name already taken'
            ], 400);
        }

        $generate_option = new GenerateOption();
        $generate_option->name = $request['name'];
        $generate_option->display_name = $request['display_name'];
        $generate_option->template_directory = $request['template_directory'];
        $generate_option->icon = $request['icon'];
        $generate_option->save();

        return response()->json([
            'success' => true,
            'data' => $generate_option,
            'message' => 'Successfully add new option !'
        ], 200);

    }

    public function ajaxDelete(Request $request, $id)
    {
        $generate_option = GenerateOption::where('id', $id)->first();

        if (empty($generate_option)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found !'
            ], 400);
        }

        $generate_option->delete();

        return response()->json([
            'success' => true,
            'data' => $generate_option,
            'message' => 'Successfully delete option !'
        ], 200);

    }
}
