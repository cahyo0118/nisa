<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;

class PostsController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new Post);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Posts data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        $posts = Post::where('title', 'like', '%' . $keyword . '%')
            ->orWhereHas('categories', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Awesome, successfully get Posts data !',
        ], 200);
    }

    public function getOne($id)
    {
        $posts = Post::find($id);

        // Data not found
        if ($posts === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Awesome, successfully get Posts data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "title" => "required|max:150",
            "category_id" => "required|max:11",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new posts !',
            ], 400);
        }

        $posts = new Post;

        $posts->updated_by = Auth::id();
        $posts->title = $request->title;
        $posts->category_id = $request->category_id;
        $posts->save();

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Awesome, successfully create new Posts !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "title" => "required|max:150",
            "category_id" => "required|max:11",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update Posts !',
            ], 400);
        }

        $posts = Post::find($id);

        $posts->updated_by = Auth::id();
        $posts->title = $request->title;
        $posts->category_id = $request->category_id;
        $posts->save();

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Awesome, successfully update Posts !',
        ], 200);

    }

    public function destroy($id)
    {
        $posts = Post::find($id);

        if ($posts === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Posts not found !',
            ], 400);

        }

        $posts->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete Posts !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $posts = Post::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete Posts !',
        ], 200);

    }
}
