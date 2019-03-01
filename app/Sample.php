<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Sample extends Model
{
    public static $validation = [
        'store' => [
            'name' => 'required|max:50',
            'description' => 'required',
        ],
        'update' => [
            'name' => 'required|max:50',
            'description' => 'required',
        ]
    ];

    public static function validate($mode = 'store', $request) {

        $validator = null;

        if ($mode == 'store') {
            // Store
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
                'description' => 'required',
            ]);
        } else if ($mode == 'update') {
            // Update
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
                'description' => 'required',
            ]);
        }

        if ($validator->fails())
        {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

    }
}
