<?php

namespace App\Helpers;

use App\GlobalVariable;
use App\Menu;
use App\Project;
use DB;

class QueryHelpers
{
    public static function castTo($type)
    {
        $result = "";

        switch ($type) {
            case "image":
                $result = "String";
                break;
            case "file":
                $result = "String";
                break;
            case "text":
                $result = "String";
                break;
            case "varchar":
                $result = "String";
                break;
            case "integer":
                $result = "int";
                break;
            case "float":
                $result = "float";
                break;
            case "double":
                $result = "double";
                break;
            case "decimal":
                $result = "decimal";
                break;
            case "char":
                $result = "char";
                break;
            case "date":
                $result = "Date";
                break;
            case "datetime":
                $result = "Timestamp";
                break;
            case "timestamp":
                $result = "Timestamp";
                break;
            case "time":
                $result = "Timestamp";
                break;
            case "bigint":
                $result = "BigInteger";
                break;
            case "tinyint":
                $result = "boolean";
                break;
        }

        return $result;
    }

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

    public static function recursiveMenu($project_id, $parent_menu_template, $child_menu_template)
    {
        $project = Project::find($project_id);

        $result = "";

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        foreach ($project->menus as $menu) {
            if (!count($menu->sub_menus)) {
                $result .= $parent_menu_template;
            } else {
                $result .= $child_menu_template;
            }
        }

        return $result;
    }

    public static function getCriteria($menu_id, $field_id)
    {

        $menu = Menu::find($menu_id);

        if (empty($menu)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        $criteria = $menu->field_criterias()->where('menu_id', $menu_id)->where('field_id', $field_id)->first();

        if (!empty($criteria)) {
            return $criteria;
        } else {
            return null;
        }
    }

    public static function getRelationCriteria($menu_id, $relation_id)
    {

        $menu = Menu::find($menu_id);

        if (empty($menu)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        $criteria = $menu->relation_criterias()->where('menu_id', $menu_id)->where('relation_id', $relation_id)->first();

        if (!empty($criteria)) {
            return $criteria;
        } else {
            return null;
        }
    }

    public static function getMenuRelationCriteria($menu_id, $relation_id, $relation_field_id)
    {

        $menu = Menu::find($menu_id);

        if (empty($menu)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        $criteria = DB::table('menu_relation_criteria')
            ->where('menu_id', $menu->id)
            ->where('relation_id', $relation_id)
            ->where('relation_field_id', $relation_field_id)
            ->first();

        error_log("getMenuRelationCriteria = " . (!empty($criteria) ? $criteria->value : "bbbb"));

        if (!empty($criteria)) {
            return $criteria;
        } else {
            return null;
        }
    }

    public static function getMenuDatasetCriteria($menu_id, $relation_id, $relation_field_id)
    {

        $menu = Menu::find($menu_id);

        if (empty($menu)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        $criteria = DB::table('menu_dataset_criterias')
            ->where('menu_id', $menu->id)
            ->where('relation_id', $relation_id)
            ->where('relation_field_id', $relation_field_id)
            ->first();

        if (!empty($criteria)) {
            return $criteria;
        } else {
            return null;
        }
    }

    public static function getTextBetweenBracket($string)
    {
        $text = 'ignore everything except this (text)';
        preg_match('#\((.*?)\)#', $string, $match);

        if (empty($match[1])) {
            return null;
        } else {
            return $match[1];
        }

    }
}
