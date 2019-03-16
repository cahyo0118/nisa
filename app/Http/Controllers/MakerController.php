<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class MakerController extends Controller
{
    public function make($project_id) {
        $project = Project::find($project_id);

        if (empty($project)) {
            Session::flash('failed', 'Project not found !');

            return redirect()->back();
        }

        foreach ($project->menus as $menu) {

        }

    }
}
