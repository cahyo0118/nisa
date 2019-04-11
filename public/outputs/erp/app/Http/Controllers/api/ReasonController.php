<?php

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Http\Request;
use Validator;

class ReasonController extends Controller
{

    public function getAll(Request $request)
    {
        $data = QueryHelpers::getData($request, new Tag);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Awesome, successfully get reasons data !',
        ], 200);
    }

    public function getAllByKeyword($keyword)
    {
        $reasons = Tag::where('id', 'like', '%' . $keyword . '%')
            ->orWhere('created_at', 'like', '%' . $keyword . '%')
            ->orWhere('updated_at', 'like', '%' . $keyword . '%')
            ->orWhere('active_flag', 'like', '%' . $keyword . '%')
            ->orWhere('updated_by', 'like', '%' . $keyword . '%')
            ->orWhere('name', 'like', '%' . $keyword . '%')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $reasons,
            'message' => 'Awesome, successfully get reasons data !',
        ], 200);
    }

    public function getOne($id)
    {
        $reason = Tag::find($id);

        // Data not found
        if ($reason === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $reason,
            'message' => 'Awesome, successfully get reason data !',
        ], 200);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            @spaceless
            'name' => ' required   | max:100 ',
@endspaceless
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed creating new reason !',
            ], 400);
        }

        $reason = new Tag;

        $reason->name = $request->name;
        $reason->save();

        return response()->json([
            'success' => true,
            'data' => $reason,
            'message' => 'Awesome, successfully create new reason !',
        ], 200);

    }

    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed update reason !',
            ], 400);
        }

        $reason = Tag::find($id);

        $reason->name = $request->name;
        $reason->save();

        return response()->json([
            'success' => true,
            'data' => $reason,
            'message' => 'Awesome, successfully update reason !',
        ], 200);

    }

    public function destroy($id)
    {
        $reason = Tag::find($id);

        if ($reason === null) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Reason not found !',
            ], 400);

        }

        $reason->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully delete reason !',
        ], 200);
    }

    public function deleteMultiple(Request $request)
    {

        $reasons = Tag::whereIn('id', json_decode($request->ids))->delete();

        return response()->json([
            'success' => true,
            'data' => $request->ids,
            'message' => 'Awesome, successfully delete reasons !',
        ], 200);

    }
}
