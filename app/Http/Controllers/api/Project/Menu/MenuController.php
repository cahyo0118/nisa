<?php

namespace App\Http\Controllers\api\Project\Menu;

use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function getAllByProjectId($project_id)
    {
        $data = Menu::where('project_id', $project_id)->get();

        return response()->json([
            'success' => true,
            'body' => $data,
            'message' => 'Successfully get data'
        ]);
    }

    public function getOne($id)
    {
        $data = Menu::find($id);

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed get data, data not found',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'body' => $data,
            'message' => 'Successfully get data'
        ]);

    }

    public function getAllSubMenu($id)
    {
        $data = Menu::where('parent_menu_id', $id)->get();

        return response()->json([
            'success' => true,
            'body' => $data,
            'message' => 'Successfully get data'
        ]);

    }

    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            Menu::$validation['store']
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

            $data = new Menu();
            $data->name = $request->name;
            $data->project_id = $request->project_id;
            $data->parent_menu_id = $request->parent_menu_id;
            $data->table_id = $request->table_id;
            $data->name = $request->name;
            $data->display_name = $request->display_name;
            $data->allow_list = !empty($request->allow_list);
            $data->allow_create = !empty($request->allow_create);
            $data->allow_single = !empty($request->allow_single);
            $data->allow_update = !empty($request->allow_update);
            $data->allow_delete = !empty($request->allow_delete);
            $data->icon = $request->icon;
            $data->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'body' => $data,
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
            Menu::$validation['update']
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

            $data = Menu::where('id', $id)->first();

            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Failed update data, data not found',
                ], 400);
            }

            $data->name = $request->name;
            $data->project_id = $request->project_id;
            $data->parent_menu_id = $request->parent_menu_id;
            $data->table_id = $request->table_id;
            $data->name = $request->name;
            $data->display_name = $request->display_name;
            $data->allow_list = !empty($request->allow_list);
            $data->allow_create = !empty($request->allow_create);
            $data->allow_single = !empty($request->allow_single);
            $data->allow_update = !empty($request->allow_update);
            $data->allow_delete = !empty($request->allow_delete);
            $data->icon = $request->icon;
            $data->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'body' => $data,
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

    public function delete($id)
    {
        $data = Menu::where('id', $id)->first();

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed delete data, data not found',
            ], 400);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'body' => null,
            'message' => 'Successfully delete data'
        ]);
    }
}
