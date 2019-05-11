<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\Brand;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;

class BrandsController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new Brand);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get Brands data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        $brands = Brand::where('name', 'like', '%' . $keyword . '%')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $brands,
            'message' => 'Awesome, successfully get Brands data !',
        ], 200);
    }

    public function getOne($id)
    {
        $brands = Brand::find($id);

        // Data not found
        if ($brands === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $brands,
            'message' => 'Awesome, successfully get Brands data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:brands,name|max:100",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new brands !',
            ], 400);
        }

        $brands = new Brand;

        $brands->updated_by = Auth::id();
        $brands->name = $request->name;
        $brands->description = $request->description;
        $brands->save();

        return response()->json([
            'success' => true,
            'data' => $brands,
            'message' => 'Awesome, successfully create new Brands !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:brands,name,{$id}|max:100",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update Brands !',
            ], 400);
        }

        $brands = Brand::find($id);

        $brands->updated_by = Auth::id();
        $brands->name = $request->name;
        $brands->description = $request->description;
        $brands->save();

        return response()->json([
            'success' => true,
            'data' => $brands,
            'message' => 'Awesome, successfully update Brands !',
        ], 200);

    }

    public function destroy($id)
    {
        $brands = Brand::find($id);

        if ($brands === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Brands not found !',
            ], 400);

        }

        $brands->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete Brands !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $brands = Brand::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete Brands !',
        ], 200);

    }
}
