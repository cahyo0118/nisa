<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Table;
use Illuminate\Http\Request;
use App\Project;
use Validator;
use Session;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = null;

        if (empty($request->keyword)) {
            $projects = Project::paginate(15);
        } else {
            $projects = Project::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        }

        return view('project.index')->with('items', $projects);
    }

    public function menus(Request $request, $project_id)
    {
        return view('menu.index')->with('id', $project_id);
    }

    public function tables(Request $request, $project_id)
    {

        $tables = null;

        if (empty($request->keyword)) {
            $tables = Table::where('project_id', $project_id)->paginate(15);
        } else {
            $tables = Table::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->where('project_id', $project_id)->paginate(15);
        }

        return view('table.index')->with('items', $tables)->with('id', $project_id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), Project::$validation['store']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = new Project();
        $project->name = $request->name;
        $project->display_name = $request->display_name;
        $project->save();

        Session::flash('success', 'Successfully store data');

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);

        if (empty($project)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('project.show')->with('item', $project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);

        if (empty($project)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('project.edit')->with('item', $project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), Project::$validation['update']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = Project::find($id);

        if (empty($project)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        $project->name = $request->name;
        $project->display_name = $request->display_name;
        $project->save();

        Session::flash('success', 'Successfully update data');

        return redirect()->route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if (empty($project)) {
            Session::flash('failed', 'Failed delete data');
            return redirect()->route('projects.index');
        }

        $project->delete();

        Session::flash('success', 'Successfully delete data');
        return redirect()->route('projects.index');
    }

    public function search(Request $request)
    {
        if (empty($request->keyword)) {
            return redirect()->route('projects.index');
        }

        $projects = Project::where('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        return view('project.index')->with('items', $projects);
    }
}
