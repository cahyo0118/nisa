<?php

namespace App\Http\Controllers;

use App\Field;
use App\Menu;
use App\Project;
use App\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $menu = Menu::where('name', $request['name'])->where('project_id', $project_id)->first();

        if (!empty($menu)) {
            return response()->json([
                'success' => false,
                'message' => 'Menu name already taken'
            ], 400);
        }

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

        $menu = Menu::where('name', $request['name'])->where('project_id', $project_id)->first();

        if (!empty($menu) && $menu->id != $parent_menu_id) {
            return response()->json([
                'success' => false,
                'message' => 'Menu name already taken'
            ], 400);
        }

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

    public function ajaxUpdateDataset(Request $request, $id, $table_id)
    {
//        return response()->json($request->json()->all());

        $random = rand(10000, 99999);

        $request = $request->json()->all();

        $menu = Menu::find($id);

        $table = Table::find($table_id);

        if (empty($menu)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        $menu->allow_list = $request['allow_list'];
        $menu->allow_create = $request['allow_create'];
        $menu->allow_single = $request['allow_single'];
        $menu->allow_update = $request['allow_update'];
        $menu->allow_delete = $request['allow_delete'];
        $menu->icon = $request['icon'];
        $menu->table_id = !empty($table) ? $table_id : null;
        $menu->save();

        if (!empty($request['dataset_relation_ids'])) {
            foreach ($request['dataset_relation_ids'] as $dataset_relation_id) {

                foreach ($request['dataset_field_ids'][$dataset_relation_id] as $dataset_field_id) {

                    if (!empty($dataset_relation_id)
                        && !empty($dataset_field_id)
                        && !empty($request['dataset_operator'][$dataset_field_id])
                        && !empty($request['dataset_value'][$dataset_field_id])
                    ) {
                        DB::table('menu_dataset_criterias')
                            ->where('menu_id', $menu->id)
                            ->where('relation_id', $dataset_relation_id)
                            ->where('relation_field_id', $dataset_field_id)
                            ->delete();

                        DB::table('menu_dataset_criterias')->insert([
                            'operator' => $request['dataset_operator'][$dataset_field_id],
                            'value' => $request['dataset_value'][$dataset_field_id],
                            'menu_id' => $menu->id,
                            'relation_id' => $dataset_relation_id,
                            'relation_field_id' => $dataset_field_id
                        ]);

                    } else {

                        DB::table('menu_dataset_criterias')
                            ->where('menu_id', $menu->id)
                            ->where('relation_id', $dataset_relation_id)
                            ->where('relation_field_id', $dataset_field_id)
                            ->delete();

                    }

                }

            }
        }

        if (!empty($request['relation_ids'])) {
            foreach ($request['relation_ids'] as $relation_id) {

                if (count($request['relation_field_ids'][$relation_id]) > 0) {
                    foreach ($request['relation_field_ids'][$relation_id] as $relation_field_id) {

//                        error_log("relation_id = " . $relation_id);
                        error_log("relation_field_id = " . $relation_field_id);
//                    error_log("relation_field_operator = " . $request['relation_field_operator'][$relation_field_id]);
//                    error_log("relation_field_value = " . $request['relation_field_value'][$relation_field_id]);
                        error_log("==========================================");

                        if (!empty($relation_id)
                            && !empty($relation_field_id)
                            && !empty($request['relation_field_operator'][$relation_field_id])
                            && !empty($request['relation_field_value'][$relation_field_id])
                        ) {
                            error_log("STORE_CRITERIA");
                            DB::table('menu_relation_criteria')
                                ->where('menu_id', $menu->id)
                                ->where('relation_id', $relation_id)
                                ->where('relation_field_id', $relation_field_id)
                                ->delete();

                            DB::table('menu_relation_criteria')->insert([
                                'operator' => $request['relation_field_operator'][$relation_field_id],
                                'value' => $request['relation_field_value'][$relation_field_id],
                                'menu_id' => $menu->id,
                                'relation_id' => $relation_id,
                                'relation_field_id' => $relation_field_id
                            ]);

                        } else {

                            DB::table('menu_relation_criteria')
                                ->where('menu_id', $menu->id)
                                ->where('relation_id', $relation_id)
                                ->where('relation_field_id', $relation_field_id)
                                ->delete();

                        }

                    }
                }

            }
        }

        if (!empty($request['load_by_reference'])) {
            foreach ($request['load_by_reference'] as $field_id => $reference_id) {

                if (!empty($reference_id)) {
                    DB::table('menu_load_references')
                        ->where('menu_id', $menu->id)
                        ->where('field_id', $field_id)
                        ->delete();

                    DB::table('menu_load_references')->insert([
                        'menu_id' => $menu->id,
                        'field_id' => $field_id,
                        'field_reference_id' => $reference_id
                    ]);
                } else {
                    DB::table('menu_load_references')
                        ->where('menu_id', $menu->id)
                        ->where('field_id', $field_id)
                        ->delete();
                }
            }
        }

        if (!empty($request['update_on_list'])) {
            foreach ($request['operator'] as $field_id => $operator) {

            }
        }

        if (!empty($request['operator'])) {

            foreach ($request['operator'] as $field_id => $operator) {
                if (!empty($operator)) {
//                    switch ($input_type) {
//                        case "relation":
//                            $dataset = [];
//
//                            $request = $request->json()->all();
//
//                            $users_table = $menu->project->tables()->where('name', 'users')->first();
//
//                            foreach ($users_table->fields as $users_field) {
//
//                                if (!empty($users_field->relation) && $users_field->relation->relation_type == "belongsto") {
//                                    $dataset[$users_field->id] = "same " . $users_field->relation->relation_name;
//                                }
//
//                            }
//
//                            $value = (string)view('menu.inputs.list-relation-users')
//                                ->with('field', $field)
//                                ->with('dataset', $dataset);
//                            break;
//                        case "default":
//                            $dataset = [];
//                            $dataset["current_user_id"] = "Current User ID";
//
//                            $value = (string)view('menu.inputs.list-relation-users')
//                                ->with('field', $field)
//                                ->with('dataset', $dataset);
//                            break;
//                        default:
//                            $value = (string)view('menu.inputs.text')
//                                ->with('field', $field)
//                                ->with('menu', $menu);
//                            break;
//                    }
                    DB::table('menu_criteria')
                        ->where('menu_id', $id)
                        ->where('field_id', $field_id)
                        ->delete();

                    $menu->field_criterias()->attach($field_id, [
                        'operator' => $operator,
                        'value' => $request['value'][$field_id],
                        'show_in_list' => $request['show_in_list'][$field_id],
                        'show_in_form' => $request['show_in_form'][$field_id]
                    ]);
                } else {
                    DB::table('menu_criteria')
                        ->where('menu_id', $id)
                        ->where('field_id', $field_id)
                        ->delete();
                }
            }

        }

        if (!empty($request['field_ids'])) {

            foreach ($request['field_ids'] as $field_id => $field) {
                if (!empty($field)) {

                    DB::table('menu_criteria')
                        ->where('menu_id', $id)
                        ->where('field_id', $field_id)
                        ->delete();

                    $menu->field_criterias()->attach($field_id, [
                        'operator' => $request['operator'][$field_id],
                        'value' => $request['value'][$field_id],
                        'required' => $request['required'][$field_id],
                        'show_in_list' => $request['show_in_list'][$field_id],
                        'show_in_form' => $request['show_in_form'][$field_id]
                    ]);
                } else {
                    DB::table('menu_criteria')
                        ->where('menu_id', $id)
                        ->where('field_id', $field_id)
                        ->delete();
                }
            }

        }

        if (!empty($request['relation_ids'])) {

            foreach ($request['relation_ids'] as $relation_id => $relation) {
                if (!empty($relation)) {

                    DB::table('relation_criterias')
                        ->where('menu_id', $id)
                        ->where('relation_id', $relation_id)
                        ->delete();

                    $menu->relation_criterias()->attach($relation_id, [
                        'show_in_list' => $request['show_in_list'][$relation_id],
                        'show_in_single' => $request['show_in_single'][$relation_id],
                        'show_in_form' => $request['show_in_form'][$relation_id]
                    ]);
                } else {
                    DB::table('relation_criterias')
                        ->where('menu_id', $id)
                        ->where('field_id', $relation_id)
                        ->delete();
                }
            }

        }

        $value = (string)view('menu.partials.menu-item')
            ->with('random', $random)
            ->with('menu', $menu)
            ->with('parent_menu', $menu)
            ->with('projects', $this->projects)
            ->with('menus', $this->menus);

        return response()->json([
            'success' => true,
            'view' => $value,
            'data' => $menu,
            'message' => 'Successfully update data'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
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

    public
    function ajaxDeleteMenu($id)
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

    public
    function search(Request $request)
    {
        if (empty($request->keyword)) {
            return redirect()->route('menus.index');
        }

        $menus = Menu::where('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        return view('menu.index')->with('items', $menus);
    }

    /* Sub Menus */
    public
    function subMenus(Request $request, $id)
    {
        $menus = null;

        if (empty($request->keyword)) {
            $menus = Menu::where('parent_menu_id', $id)->paginate(15);
        } else {
            $menus = Menu::orWhere('name', 'LIKE', '%' . $request->keyword . '%')->paginate(15);
        }

        return view('menu.index')->with('items', $menus);
    }


    public
    function getAllMenuByProjectId($project_id)
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

    public
    function getAllSubMenuByMenuId($project_id, $parent_menu_id)
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

    public
    function ajaxFillCriteria(Request $request, $menu_id, $field_id)
    {
        $request = $request->json()->all();

        $field = Field::find($field_id);

        if (empty($field)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        DB::table('menu_criteria')
            ->where('menu_id', $menu_id)
            ->where('variable_id', $field_id)
            ->delete();

        $field->menu_criterias()->attach($menu_id, [
            'operator' => $request['operator'],
            'value' => $request['value']
        ]);

//        $value = (string)view('generate-options.partials.global-variable-item')
//            ->with('item', $field->generate_option)
//            ->with('global_variable', $field);

        return response()->json([
            'success' => true,
            'data' => $field,
//            'view' => $value,
            'message' => 'Successfully add new global variable'
        ], 200);
    }

    public
    function inputsView(Request $request, $menu_id, $field_id, $input_type)
    {
        $value = null;

        $menu = Menu::find($menu_id);
        $field = Field::find($field_id);

        if (empty($field) || empty($menu) || empty($menu->table)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        switch ($input_type) {
            case "relation":
                $dataset = [];

                $users_table = $menu->project->tables()->where('name', 'users')->first();

                foreach ($users_table->fields as $users_field) {

                    if (!empty($users_field->relation) && $users_field->relation->relation_type == "belongsto") {
                        $dataset[$users_field->id] = "same " . $users_field->relation->relation_name;
                    }

                }

                $value = (string)view('menu.inputs.list-relation-users')
                    ->with('field', $field)
                    ->with('menu', $menu)
                    ->with('dataset', $dataset);
                break;
            case "default":
                $dataset = [];
                $dataset["current_user_id"] = "Current User ID";

                $value = (string)view('menu.inputs.list-relation-users')
                    ->with('field', $field)
                    ->with('menu', $menu)
                    ->with('dataset', $dataset);
                break;
            default:
                $value = (string)view('menu.inputs.text')
                    ->with('field', $field)
                    ->with('menu', $menu);
                break;
        }

        return response()->json([
            'success' => true,
//            'data' => $field,
            'view' => $value,
            'message' => 'Successfully add new global variable'
        ], 200);
    }

}
