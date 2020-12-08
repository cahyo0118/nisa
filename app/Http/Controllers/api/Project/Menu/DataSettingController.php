<?php

namespace App\Http\Controllers\api\Project\Menu;

use App\Field;
use App\Menu;
use App\MenuCriteria;
use App\MenuDatasetCriteria;
use App\MenuLoadReference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DataSettingController extends Controller
{

    /*Relations*/
    public function getFields($id)
    {
        $menu = Menu::find($id);

        if (empty($menu)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed get data, data not found',
            ], 400);
        }

        $data = Field::with([
            'relation.dataset_criterias',
            'load_reference.field_reference',
            'criteria',
            'static_datasets'
        ])
            ->where('table_id', $menu->table_id)
            ->get();

        return response()->json([
            'success' => true,
            'body' => $data,
            'message' => 'Successfully get data'
        ]);

    }


    public function updateCriteria(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'menu_id' => 'required',
                'field_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update criteria data',
            ], 400);
        }

        try {

            DB::beginTransaction();

            $data = MenuCriteria::where('menu_id', $request->menu_id)
                ->where('field_id', $request->field_id)
                ->first();

            if (empty($data)) {
                $data = new MenuCriteria();
            }

            $data->menu_id = $request->menu_id;
            $data->field_id = $request->field_id;
            $data->operator = $request->operator;
            $data->value = $request->value;
            $data->required = !empty($request->required);
            $data->show_in_list = !empty($request->show_in_list);
            $data->show_in_form = !empty($request->show_in_form);
            $data->show_in_single = !empty($request->show_in_single);

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

    public function updateDatasetCriteria(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'menu_id' => 'required',
                'relation_id' => 'required',
                'relation_field_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update criteria data',
            ], 400);
        }

        try {

            DB::beginTransaction();

            $data = MenuDatasetCriteria::where('menu_id', $request->menu_id)
                ->where('relation_id', $request->relation_id)
                ->where('relation_field_id', $request->relation_field_id)
                ->first();

            if (empty($data)) {
                $data = new MenuDatasetCriteria();
            }

            $data->menu_id = $request->menu_id;
            $data->relation_id = $request->relation_id;
            $data->relation_field_id = $request->relation_field_id;

            $data->operator = $request->operator;
            $data->value = $request->value;

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

    public function updateLoadReference(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'menu_id' => 'required',
                'field_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update criteria data',
            ], 400);
        }

        try {

            DB::beginTransaction();

            $data = MenuLoadReference::where('menu_id', $request->menu_id)
                ->where('field_id', $request->field_id)
                ->first();

            if (empty($data)) {
                $data = new MenuLoadReference();
            }

            $data->menu_id = $request->menu_id;
            $data->field_id = $request->field_id;
            $data->field_reference_id = $request->field_reference_id;

            if (empty($request->field_reference_id)) {
                $data->delete();
            } else {
                $data->save();
            }


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
}
