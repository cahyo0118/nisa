<?php

namespace App\Http\Controllers\api\Project\Menu;

use App\Field;
use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        $data = Field::where('table_id', $menu->table_id)->get();

        return response()->json([
            'success' => true,
            'body' => $data,
            'message' => 'Successfully get data'
        ]);

    }
}
