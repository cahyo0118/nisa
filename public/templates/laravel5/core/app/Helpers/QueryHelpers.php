<?php

namespace App\Helpers;

class QueryHelpers
{
    public static function getData($request, $model, $with = [])
    {
        $data = $model;

        if (!empty($request->with)) {
            foreach ((array)$request->with as $withItem) {
                array_push($with, $withItem);
            }
        }

        if (!empty($request->order_by)) {
            $data = $model->with($with)->orderBy($request->order_by, !empty($request->order_type) ? $request->order_type : 'asc');
        } else {
            $data = $model->with($with);
        }


        if (!empty($request->all)) {
            $data = $data->get();
        } else {
            $data = $data->paginate(env('ITEM_PER_PAGE'));
        }


        return $data;
    }

    public static function getSingleData($request, $model, $with = [])
    {
        $data = $model;

        if (!empty($request->with)) {
            foreach ((array)$request->with as $withItem) {
                array_push($with, $withItem);
            }
        }

        $data = $model->with($with);

        $data = $data->first();


        return $data;
    }

    public static function getDataByQueryBuilder($request, $query, $with = [])
    {
        $data = $query;

        if (!empty($request->with)) {
            foreach ((array)$request->with as $withItem) {
                array_push($with, $withItem);
            }
        }

        if (!empty($request->order_by)) {
            $data = $query->with($with)->orderBy($request->order_by, !empty($request->order_type) ? $request->order_type : 'asc');
        } else {
            $data = $query->with($with);
        }


        if (!empty($request->all)) {
            $data = $data->get();
        } else {
            $data = $data->paginate(env('ITEM_PER_PAGE'));
        }


        return $data;
    }
}
