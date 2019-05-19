{!! $php_prefix !!}

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
@foreach($project->menus as $menu)
        $permission = Permission::where("name", "{{ snake_case(str_plural($menu->name)) }}_create")->first();
        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "{{ snake_case(str_plural($menu->name)) }}_create";
            $permission->description = "Create {{ ucwords(str_replace('_', ' ', str_plural($menu->name))) }}";
            $permission->save();
        }

        $permission = Permission::where("name", "{{ snake_case(str_plural($menu->name)) }}_read")->first();
        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "{{ snake_case(str_plural($menu->name)) }}_read";
            $permission->description = "Read {{ ucwords(str_replace('_', ' ', str_plural($menu->name))) }}";
            $permission->save();
        }

        $permission = Permission::where("name", "{{ snake_case(str_plural($menu->name)) }}_update")->first();
        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "{{ snake_case(str_plural($menu->name)) }}_update";
            $permission->description = "Update {{ ucwords(str_replace('_', ' ', str_plural($menu->name))) }}";
            $permission->save();
        }

        $permission = Permission::where("name", "{{ snake_case(str_plural($menu->name)) }}_delete")->first();
        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "{{ snake_case(str_plural($menu->name)) }}_delete";
            $permission->description = "Delete {{ ucwords(str_replace('_', ' ', str_plural($menu->name))) }}";
            $permission->save();
        }
@endforeach
    }
}
