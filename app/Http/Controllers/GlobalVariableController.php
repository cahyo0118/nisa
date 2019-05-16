<?php

namespace App\Http\Controllers;

use App\GlobalVariable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalVariableController extends Controller
{
    public function ajaxStore(Request $request, $generate_option_id, $variable_id)
    {
        $request = $request->json()->all();

        $global_variable = GlobalVariable::where('name', $request['name'])
            ->where('generate_option_id', $generate_option_id)
            ->first();

        if (empty($variable_id)) {

//            return $global_variable;
            if (!empty($global_variable)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Variable name already exist'
                ], 400);
            } else {
                $global_variable = new GlobalVariable();
            }

        } else {

            $global_variable = GlobalVariable::find($variable_id);

            if (empty($global_variable)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ], 400);
            }

        }

        $global_variable->name = $request['name'];
        $global_variable->value = $request['value'];
        $global_variable->generate_option_id = $generate_option_id;
        $global_variable->save();

        $value = (string)view('generate-options.partials.global-variable-item')
            ->with('item', $global_variable->generate_option)
            ->with('global_variable', $global_variable);

        return response()->json([
            'success' => true,
            'data' => $global_variable,
            'view' => $value,
            'message' => 'Successfully add new global variable'
        ], 200);
    }

    public function ajaxDelete(Request $request, $variable_id)
    {
        $global_variable = GlobalVariable::find($variable_id);

        if (empty($global_variable)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        $global_variable->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully delete global variable'
        ], 200);
    }

    public function ajaxFillVariable(Request $request, $project_id, $variable_id)
    {
        $request = $request->json()->all();

        $global_variable = GlobalVariable::find($variable_id);

        if (empty($global_variable)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        DB::table('variable_project')
            ->where('project_id', $project_id)
            ->where('variable_id', $variable_id)
            ->delete();

        $global_variable->projects()->attach($project_id, [
            'value' => $request['value']
        ]);

        $value = (string)view('generate-options.partials.global-variable-item')
            ->with('item', $global_variable->generate_option)
            ->with('global_variable', $global_variable);

        return response()->json([
            'success' => true,
            'data' => $global_variable,
            'view' => $value,
            'message' => 'Successfully add new global variable'
        ], 200);
    }
}
