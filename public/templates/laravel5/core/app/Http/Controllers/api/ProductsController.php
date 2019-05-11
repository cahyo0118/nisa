<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use Validator;
use Auth;
use Hash;

class ProductsController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new Product);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Products data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        $products = Product::where('name', 'like', '%' . $keyword . '%')
            ->orWhereHas('brand', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Awesome, successfully get Products data !',
        ], 200);
    }

    public function getOne($id)
    {
        $products = Product::find($id);

        // Data not found
        if ($products === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Awesome, successfully get Products data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|max:150",
            "picture" => "required",
            "brand_id" => "required|max:11",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new products !',
            ], 400);
        }

        $products = new Product;

        $products->updated_by = Auth::id();
        $products->name = $request->name;
        $products->picture = $request->picture;
        $products->brand_id = $request->brand_id;

//        if (!empty($products->image)) {
//            if (file_exists('votes/images/' . $products->image)) {
//                unlink(public_path('votes/images/' . $products->image));
//            }
//        }
//
//        $image = $request->file('image');
//        $filename = time() . '.' . $image->getClientOriginalName();
//        $path = public_path('/votes/images/' . $filename);
//        Image::make($image->getRealPath())->save($path);
//        $products->image = 'votes/images/' . $filename;

        $products->save();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully update current vote image !',
        ], 200);

        $products->save();

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Awesome, successfully create new Products !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|max:150",
            "picture" => "required|max:191",
            "brand_id" => "required|max:11",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update Products !',
            ], 400);
        }

        $products = Product::find($id);

        $products->updated_by = Auth::id();
        $products->name = $request->name;
        $products->picture = $request->picture;
        $products->brand_id = $request->brand_id;
        $products->save();

        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Awesome, successfully update Products !',
        ], 200);

    }

    public function destroy($id)
    {
        $products = Product::find($id);

        if ($products === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Products not found !',
            ], 400);

        }

        $products->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete Products !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $products = Product::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete Products !',
        ], 200);

    }
}
