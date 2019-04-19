<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\Project;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;

class ProjectsController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new Project);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Projects data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        $projects = Project::where('name', 'like', '%' . $keyword . '%')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $projects,
            'message' => 'Awesome, successfully get Projects data !',
        ], 200);
    }

    public function getOne($id)
    {
        $projects = Project::find($id);

        // Data not found
        if ($projects === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $projects,
            'message' => 'Awesome, successfully get Projects data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:projects,name|max:100",
            "address" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new projects !',
            ], 400);
        }

        $projects = new Project;

        $projects->updated_by = Auth::id();
        $projects->name = $request->name;
        $projects->address = $request->address;
        $projects->save();

        return response()->json([
            'success' => true,
            'data' => $projects,
            'message' => 'Awesome, successfully create new Projects !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:projects,name,{$id}|max:100",
            "address" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update Projects !',
            ], 400);
        }

        $projects = Project::find($id);

        $projects->updated_by = Auth::id();
        $projects->name = $request->name;
        $projects->address = $request->address;
        $projects->save();

        return response()->json([
            'success' => true,
            'data' => $projects,
            'message' => 'Awesome, successfully update Projects !',
        ], 200);

    }

    public function destroy($id)
    {
        $projects = Project::find($id);

        if ($projects === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Projects not found !',
            ], 400);

        }

        $projects->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete Projects !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $projects = Project::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete Projects !',
        ], 200);

    }
}
