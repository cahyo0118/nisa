<?php

namespace App\Http\Controllers\api\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VariableValueController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'variable_id' => 'required',
                'value' => 'required',
                'project_id' => 'required',
                'generate_option_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed add data',
            ], 400);
        }

        try {

            DB::beginTransaction();

            $variable_value = DB::table('variable_project')->insert([
                'value' => $request->value,
                'project_id' => $request->project_id,
                'variable_id' => $request->variable_id,
                'generate_option_id' => $request->generate_option_id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'body' => $variable_value,
                'message' => 'Successfully add data'
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed add data',
                'messageSystem' => $exception->getMessage(),
            ], 400);
        }

    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'variable_id' => 'required',
                'value' => 'required',
                'project_id' => 'required',
                'generate_option_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update data',
            ], 400);
        }

        try {

            DB::beginTransaction();

            $variable_value = DB::table('variable_project')
                ->where('id', $id)
                ->update([
                    'value' => $request->value,
                    'project_id' => $request->project_id,
                    'variable_id' => $request->variable_id,
                    'generate_option_id' => $request->generate_option_id,
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'body' => $variable_value,
                'message' => 'Successfully update data'
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed update data',
                'messageSystem' => $exception->getMessage(),
            ], 400);
        }

    }

    public function delete(Request $request, $id)
    {

        try {

            DB::beginTransaction();

            $variable_value = DB::table('variable_project')
                ->where('id', $id)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'body' => $variable_value,
                'message' => 'Successfully delete data'
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed delete data',
                'messageSystem' => $exception->getMessage(),
            ], 400);
        }

    }
}
