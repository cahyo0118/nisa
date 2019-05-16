<?php

namespace App\Helpers;

use App\GlobalVariable;
use App\Project;

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

    public static function getVariable($template_name, $variable_name, $project_id)
    {

        $project = Project::find($project_id);

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        $variable = $project->variables()->whereHas('generate_option', function ($query) use ($template_name) {
            $query->where('name', $template_name);
        })->where('name', $variable_name)->first();

        if (!empty($variable)) {
            return $variable->pivot->value;
        } else {
            return QueryHelpers::getDefaultVariable($template_name, 'CLIENT_ID', $project->id);
        }
    }

    public static function getDefaultVariable($template_name, $variable_name, $project_id)
    {

        $project = Project::find($project_id);

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        $default_variable = GlobalVariable::whereHas('generate_option', function ($query) use ($template_name) {
            $query->where('name', $template_name);
        })->where('name', $variable_name)->first();

        return $default_variable->value;
    }
}
