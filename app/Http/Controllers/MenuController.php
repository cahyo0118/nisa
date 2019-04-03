<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Project;
use App\Table;
use Illuminate\Http\Request;
use Validator;
use Session;

class MenuController extends Controller
{

    private $projects, $menus;

    public function __construct()
    {
        $this->projects = Project::pluck('display_name', 'id');
        $this->menus = Menu::pluck('display_name', 'id')->prepend('No parent', '');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $menus = null;

        if (empty($request->keyword)) {
            $menus = Menu::where('parent_menu_id', null)->paginate(15);
        } else {
            $menus = Menu::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        }

        return view('menu.index')->with('items', $menus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::pluck('display_name', 'id');
        $menus = Menu::pluck('display_name', 'id')->prepend('No parent', '');

        return view('menu.create')->with('projects', $projects)->with('menus', $menus);
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
        $validator = Validator::make($request->all(), Menu::$validation['store']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $menu = new Menu();
        $menu->name = $request->name;
        $menu->display_name = $request->display_name;
        $menu->parent_menu_id = $request->parent_menu_id;
        $menu->project_id = $request->project_id;
        $menu->save();

        Session::flash('success', 'Successfully store data');

        return redirect()->route('menus.index');
    }

    public function ajaxStore(Request $request, $project_id, $parent_menu_id)
    {
        $random = rand(10000, 99999);

        $request = $request->json()->all();

        $menu = new Menu();
        $menu->name = $request['name'];
        $menu->display_name = $request['display_name'];
        $menu->parent_menu_id = !empty($request['parent_menu_id']) ? $request['parent_menu_id'] : null;
        $menu->project_id = $request['project_id'];
        $menu->save();

        $value = (string)view('menu.partials.menu-item')
            ->with('random', $random)
            ->with('menu', $menu)
            ->with('parent_menu', $menu)
            ->with('projects', $this->projects)
            ->with('menus', $this->menus);

        return response()->json([
            'success' => true,
            'view' => $value,
            'message' => 'All menus in project !'
        ], 200);

    }

    public function ajaxUpdate(Request $request, $project_id, $parent_menu_id)
    {
        $random = rand(10000, 99999);

        $request = $request->json()->all();

        $menu = Menu::find($parent_menu_id);
        $menu->name = $request['name'];
        $menu->display_name = $request['display_name'];
        $menu->parent_menu_id = !empty($request['parent_menu_id']) ? $request['parent_menu_id'] : null;
        $menu->project_id = $request['project_id'];
        $menu->save();

        $value = (string)view('menu.partials.menu-item')
            ->with('random', $random)
            ->with('menu', $menu)
            ->with('parent_menu', $menu)
            ->with('projects', $this->projects)
            ->with('menus', $this->menus);

        return response()->json([
            'success' => true,
            'view' => $value,
            'message' => 'All menus in project !'
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = Menu::find($id);

        if (empty($menu)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('menu.show')->with('item', $menu);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = Menu::find($id);

        $projects = Project::pluck('display_name', 'id');
        $menus = Menu::pluck('display_name', 'id')->prepend('No parent', '');

        if (empty($menu)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        return view('menu.edit')->with('item', $menu)->with('projects', $projects)->with('menus', $menus);
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
        $validator = Validator::make($request->all(), Menu::$validation['update']);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $menu = Menu::find($id);

        if (empty($menu)) {
            Session::flash('failed', 'Data not found');
            return redirect()->back();
        }

        $menu->name = $request->name;
        $menu->display_name = $request->display_name;
        $menu->parent_menu_id = $request->parent_menu_id;
        $menu->project_id = $request->project_id;
        $menu->save();

        Session::flash('success', 'Successfully update data');

        return redirect()->route('menus.index');
    }

    public function ajaxUpdateDataset($id, $table_id)
    {

        $menu = Menu::find($id);

        $table = Table::find($table_id);

        if (empty($menu) || empty($table)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed delete data'
            ], 400);
        }

        $menu->table_id = $table_id;
        $menu->save();

        return response()->json([
            'success' => true,
            'message' => 'Successfully update data'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (empty($menu)) {
            Session::flash('failed', 'Failed delete data');
            return redirect()->route('menus.index');
        }

        $menu->delete();

        Session::flash('success', 'Successfully delete data');
        return redirect()->route('menus.index');
    }

    public function ajaxDeleteMenu($id)
    {
        $menu = Menu::find($id);

        if (empty($menu)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed delete data'
            ], 400);
        }

        $menu->delete();

        return response()->json([
            'success' => false,
            'message' => 'Successfully delete data'
        ], 200);
    }

    public function search(Request $request)
    {
        if (empty($request->keyword)) {
            return redirect()->route('menus.index');
        }

        $menus = Menu::where('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        return view('menu.index')->with('items', $menus);
    }

    /* Sub Menus */
    public function subMenus(Request $request, $id)
    {
        $menus = null;

        if (empty($request->keyword)) {
            $menus = Menu::where('parent_menu_id', $id)->paginate(15);
        } else {
            $menus = Menu::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        }

        return view('menu.index')->with('items', $menus);
    }


    public function getAllMenuByProjectId($project_id)
    {

        $menus = Menu::pluck('display_name', 'id')->prepend('No parent', '');

        $menu_ids = [];

        $random = rand(10000, 99999);

        $project = Project::find($project_id);

        $value = '';

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Data not found !'
            ], 400);
        }

        foreach ($project->menus()->where('parent_menu_id', null)->get() as $menu) {

            array_push($menu_ids, $menu->id);

            $value .= (string)view('menu.partials.menu-item')
                ->with('random', $random)
                ->with('menu', $menu)
                ->with('projects', $this->projects)
                ->with('menus', $this->menus);
        }

        return response()->json([
            'success' => true,
            'view' => $value,
            'menu_ids' => $menu_ids,
            'message' => 'All menus in project !'
        ], 200);

    }

    public function getAllSubMenuByMenuId($project_id, $parent_menu_id)
    {
        $menu_ids = [];

        $random = rand(10000, 99999);

        $value = '';

        $project = Project::find($project_id);

        $menu = Menu::find($parent_menu_id);

        if (empty($project) || empty($menu)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Data not found !'
            ], 400);
        }

        if (empty(count($menu->sub_menus))) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Data not found !'
            ], 202);
        }

        foreach ($menu->sub_menus as $sub_menu) {

            array_push($menu_ids, $sub_menu->id);

            $value .= (string)view('menu.partials.menu-item')
                ->with('random', $random)
                ->with('menu', $sub_menu)
                ->with('parent_menu', $menu)
                ->with('projects', $this->projects)
                ->with('menus', $this->menus);
        }

        return response()->json([
            'success' => true,
            'view' => $value,
            'menu_ids' => $menu_ids,
            'message' => 'All menus in project !'
        ], 200);

    }

}
