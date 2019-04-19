<?php

namespace App\Helpers;

class QueryHelpers
{
    public static function getData($request, $model)
    {
        $data = null;

        if (!empty($request->order_by)) {
            $data = $model->orderBy($request->order_by, !empty($request->order_type) ? $request->order_type : 'asc')->paginate(env('ITEM_PER_PAGE'));
        } else {
            $data = $model->paginate(env('ITEM_PER_PAGE'));
        }

        return $data;
    }
}
