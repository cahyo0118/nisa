<?php

namespace App\Http\Controllers\api\Project\ImportDB;

use App\Field;
use App\Helpers\QueryHelpers;
use App\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImportDBController extends Controller
{
    public function readDB(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'path' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed import data',
            ], 400);
        }

        $dump_file_location = exec("pwd") . '/' . $request->path;

        $random_code = time() . "_" . Str::random(6);

        /*CREATE DB*/
        exec(
            'mysql'
            . ' -u ' . env('DB_USERNAME')
            . ' -p' . env('DB_PASSWORD')
            . ' -e "CREATE DATABASE ' . $random_code . '"'
        );

        /*IMPORT STRUCTURE*/
        exec(
            'mysql'
            . ' -u ' . env('DB_USERNAME')
            . ' -p' . env('DB_PASSWORD')
            . ' ' . $random_code . ' < ' . $dump_file_location
        );

        $tables = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='{$random_code}'");

        foreach ($tables as $table) {
            $fields = DB::select(
                "SELECT"
                . " `COLUMN_NAME`, "
                . " `COLUMN_DEFAULT`, "
                . " `IS_NULLABLE`, "
                . " `DATA_TYPE`, "
                . " `COLUMN_TYPE`, "
                . " `COLUMN_KEY`, "
                . " `EXTRA` "
//                . " * "
                . " FROM INFORMATION_SCHEMA.COLUMNS "
                . " WHERE TABLE_NAME='{$table->TABLE_NAME}' "
                . " AND TABLE_SCHEMA='{$random_code}'"
            );
            $table->fields = $fields;
        }

        /*DROP DB*/
        exec(
            'mysql'
            . ' -u ' . env('DB_USERNAME')
            . ' -p' . env('DB_PASSWORD')
            . ' -e "DROP DATABASE ' . $random_code . '"'
        );

        return response()->json([
            'success' => true,
            'body' => $tables,
            'message' => 'Successfully get data'
        ]);
    }

    public function uploadTemp(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'file' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed import data',
            ], 400);
        }

        try {
            $tempStoragePath = 'files/temp/';

            $uploadedFile = $request->file('file');

            if (!File::isDirectory($tempStoragePath)) {
                File::makeDirectory($tempStoragePath, 0777, true, true);
            }

            $filename = time() . "." . Str::random(6) . "." . $uploadedFile->getClientOriginalExtension();

            if (empty($request->file('file'))) {

                return response()->json([
                    'success' => false,
                    'body' => null,
                    'message' => 'Oops, file not found !',
                ], 400);

            } else if (!empty($request->file('file')) && $uploadedFile->getClientOriginalExtension() == 'sql') {

                $targetPath = public_path('/' . $tempStoragePath . $filename);

                file_put_contents($targetPath, file_get_contents($uploadedFile));

                return response()->json([
                    'success' => true,
                    'body' => [
                        "filename" => $filename,
                        "path" => $tempStoragePath . $filename
                    ],
                    'message' => 'Awesome, successfully upload file !',
                ], 200);

            } else {

                return response()->json([
                    'success' => false,
                    'body' => null,
                    'message' => 'Oops, use file with the extension sql',
                ], 400);

            }

        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'body' => null,
                'message' => 'Oops, somethings when wrong !',
                'systemMessage' => $exception->getMessage(),
            ], 400);
        }

    }

    public function importDBToProject(Request $request, $project_id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tables' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Failed import data',
            ], 400);
        }

        try {

            DB::beginTransaction();

            foreach ($request->tables as $t) {

                $table = Table::where('name', $t['TABLE_NAME'])->first();

                if (empty($table)) {
                    $table = new Table();
                }

                $table->project_id = $project_id;
                $table->name = $t['TABLE_NAME'];
                $table->display_name = $t['TABLE_NAME'];
                $table->save();

                foreach ($t['fields'] as $f) {

                    $field = Field::where('name', $f['COLUMN_NAME'])->first();

                    if (empty($field)) {
                        $field = new Field();
                        $field->name = $f['COLUMN_NAME'];
                        $field->display_name = title_case(str_replace('_', ' ', $f['COLUMN_NAME']));
                        $field->type = $f['DATA_TYPE'];
                        $field->input_type = "text";
                        $field->length = (int)QueryHelpers::getTextBetweenBracket($f['COLUMN_TYPE']);
                        $field->index = 0;
                        $field->default = 0;
                        $field->notnull = $f['IS_NULLABLE'] == 'NO';
                        $field->unsigned = !empty(strpos($f['COLUMN_TYPE'], 'unsigned'));
                        $field->ai = !empty(strpos($f['EXTRA'], 'auto_increment'));
                        $field->searchable = false;
                        $field->table_id = $table->id;
                        $field->save();
                    }

                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'body' => null,
                'message' => 'Awesome, successfully import db !',
            ], 200);

        } catch (\Exception $exception) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'body' => null,
                'message' => 'Oops, somethings when wrong !',
                'systemMessage' => $exception->getTraceAsString(),
            ], 400);
        }

    }
}
