<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;

class TagsController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new Tag);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Tags data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        $tags = Tag::where('name', 'like', '%' . $keyword . '%')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $tags,
            'message' => 'Awesome, successfully get Tags data !',
        ], 200);
    }

    public function getOne($id)
    {
        $tags = Tag::find($id);

        // Data not found
        if ($tags === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $tags,
            'message' => 'Awesome, successfully get Tags data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:tags,name|max:100",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new tags !',
            ], 400);
        }

        $tags = new Tag;

        $tags->updated_by = Auth::id();
        $tags->name = $request->name;
        $tags->description = $request->description;
        $tags->save();

        return response()->json([
            'success' => true,
            'data' => $tags,
            'message' => 'Awesome, successfully create new Tags !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:tags,name,{$id}|max:100",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update Tags !',
            ], 400);
        }

        $tags = Tag::find($id);

        $tags->updated_by = Auth::id();
        $tags->name = $request->name;
        $tags->description = $request->description;
        $tags->save();

        return response()->json([
            'success' => true,
            'data' => $tags,
            'message' => 'Awesome, successfully update Tags !',
        ], 200);

    }

    public function destroy($id)
    {
        $tags = Tag::find($id);

        if ($tags === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Tags not found !',
            ], 400);

        }

        $tags->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete Tags !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $tags = Tag::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete Tags !',
        ], 200);

    }
}
