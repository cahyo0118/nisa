<?php

namespace App\Http\Controllers\api\Project;

use App\GenerateOption;
use App\Project;
use App\Http\Controllers\Controller;

class ProjectGenerateOptionController extends Controller
{
    public function getGlobalVariables($project_id, $generate_option_id)
    {
        $project = Project::find($project_id);

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed get data, project not found',
            ], 400);
        }

        $generate_option = GenerateOption::find($generate_option_id);

        if (empty($generate_option)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed get data, generate option not found',
            ], 400);
        }

        $project_variables = $project->variables()->where('generate_option_id', $generate_option_id)->get();


        return response()->json([
            'success' => true,
            'body' => $project_variables,
            'message' => 'Successfully get data'
        ]);

    }

}
