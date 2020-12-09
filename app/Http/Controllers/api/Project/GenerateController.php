<?php

namespace App\Http\Controllers\api\Project;

use App\DefaultHelpers;
use App\Project;
use App\Relation;
use Chumper\Zipper\Zipper;
use Zip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;

class GenerateController extends Controller
{
    public function generateProject(Request $request, $id, $template)
    {

        $start_project_file = "";
        $files = [];

        set_time_limit(0);
        error_log($template);
        $zipper = new Zipper;

        $php_prefix = "<?php";

        $project = Project::find($id);

        if (empty($project)) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 400);
        }

        try {
//            Creating Folder
            $blade_directory = "templates.$template.base";
            $template_directory = "resources/views/templates/$template";
            $project_directory = "outputs/$project->name-$template";
            if (!is_dir($project_directory)) mkdir($project_directory, 0777, true);

            if ($request->generate_directory) {

                $start_project_file = "templates/$template/core.zip";

                Storage::copy("templates/$template/core.zip", $project_directory . "/core.zip");

//                return response()->json([], 200);
                $zip = Zip::open($project_directory . '/core.zip');

                $zip->extract($project_directory);

//                $zipper->make($project_directory . '/core.zip')->extractTo($project_directory);

                Storage::delete($project_directory . '/core.zip');
            }

//            Read templates map
            $template_maps = json_decode(file_get_contents(base_path($template_directory . "/templates.json")));

//            return response()->json($template_maps->files);

//            Generate cores file
            foreach ($template_maps->files->core as $core_file) {

//                Create file and directory
                if (!is_dir($project_directory . $core_file->target_path))
                    mkdir($project_directory . $core_file->target_path, 0777, true);

                $filepath = $project_directory . $core_file->target_path . "/" . $core_file->target_filename;

                array_push($files, $filepath);

                file_put_contents(
                    $filepath,
                    (string)view(
                        $blade_directory
                        . str_replace("/", ".", $core_file->resource_path) // change ordinary path to blade format
                        . ($core_file->resource_path !== "/" ? "." : "") // if resource path is base path
                        . $core_file->resource_filename // template file name
                    )
                        ->with('project', $project)
                        ->with('php_prefix', $php_prefix)
                        ->with('php_prefix', $php_prefix)
                        ->with('template_name', $template)

                );
            }

//            Generate menus file
            foreach ($template_maps->files->menus as $menu_file) {

                foreach ($project->menus as $menu) {

//                Create file and directory
                    if (!is_dir(
                        $project_directory
                        . DefaultHelpers::render(
                            Blade::compileString($menu_file->target_path), [
                                'menu' => $menu,
                                'project' => $project
                            ]
                        )))
                        mkdir(
                            $project_directory
                            . DefaultHelpers::render(
                                Blade::compileString($menu_file->target_path), [
                                    'menu' => $menu,
                                    'project' => $project
                                ]
                            ), 0777, true);

                    $filepath = $project_directory
                        . DefaultHelpers::render(
                            Blade::compileString($menu_file->target_path), [
                                'menu' => $menu,
                                'project' => $project
                            ]
                        )
                        . "/"
                        . DefaultHelpers::render(Blade::compileString($menu_file->target_filename), ['menu' => $menu]);

                    array_push($files, $filepath);

                    file_put_contents(
                        $filepath,
                        (string)view(
                            $blade_directory
                            . str_replace("/", ".", $menu_file->resource_path) // change ordinary path to blade format
                            . ($menu_file->resource_path !== "/" ? "." : "") // if resource path is base path
                            . $menu_file->resource_filename // template file name
                        )->with('menu', $menu)
                            ->with('project', $project)
                            ->with('php_prefix', $php_prefix)

                    );

                }
            }

            //            Generate tables file
            foreach ($template_maps->files->tables as $table_file) {

                foreach ($project->tables as $table_index => $table) {

//                Create file and directory
                    if (!is_dir($project_directory . $table_file->target_path))
                        mkdir($project_directory . $table_file->target_path, 0777, true);

                    $filepath = $project_directory . $table_file->target_path . "/" . DefaultHelpers::render(
                            Blade::compileString($table_file->target_filename), [
                                'table' => $table,
                                'project' => $project,
                                'table_index' => $table_index
                            ]
                        );

                    array_push($files, $filepath);

                    file_put_contents(
                        $filepath,
                        (string)view(
                            $blade_directory
                            . str_replace("/", ".", $table_file->resource_path) // change ordinary path to blade format
                            . ($table_file->resource_path !== "/" ? "." : "") // if resource path is base path
                            . $table_file->resource_filename // template file name
                        )->with('table', $table)->with('project', $project)->with('php_prefix', $php_prefix)

                    );

                    error_log("++++++++++++++++++++++++++++++++++++++");
                    error_log(100000 + $project->id + $table_index);
//                    error_log(floor(('%' . str_replace(':', '', date('h:i:s'))) . "1"));
//                    error_log(sprintf('%06d', str_replace(':', '', date('h:i:s')) . $table_index));
                }
            }

            //            Generate relation tables file
            foreach ($template_maps->files->relations as $relation_file) {

                foreach ($project->tables as $table_index => $table) {

                    foreach (Relation::where([
                        'local_table_id' => $table->id,
//                        'relation_type' => 'belongstomany',
                    ])->get() as $relation_index => $relation) {

//                Create file and directory
                        if (!is_dir($project_directory . $relation_file->target_path))
                            mkdir($project_directory . $relation_file->target_path, 0777, true);

                        $filepath = $project_directory . $relation_file->target_path . "/" . DefaultHelpers::render(
                                Blade::compileString($relation_file->target_filename), [
                                    'relation' => $relation,
                                    'project' => $project,
                                    'relation_index' => $relation_index,
                                    'table_index' => $table_index
                                ]
                            );

                        array_push($files, $filepath);

                        file_put_contents(
                            $filepath,
                            (string)view(
                                $blade_directory
                                . str_replace("/", ".", $relation_file->resource_path) // change ordinary path to blade format
                                . ($relation_file->resource_path !== "/" ? "." : "") // if resource path is base path
                                . $relation_file->resource_filename // template file name
                            )->with('relation', $relation)->with('project', $project)->with('php_prefix', $php_prefix)

                        );


                    }
                }

            }


        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'messageSystem' => $exception->getTraceAsString()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                "core" => $start_project_file,
                "files" => $files
            ],
            'message' => 'Generated'
        ], 200);
    }
}
