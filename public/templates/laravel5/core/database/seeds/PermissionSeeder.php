<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = new Permission;
        $permission->name = "user_create";
        $permission->display_name = "User Create";
        $permission->save();

        $permission = new Permission;
        $permission->name = "user_read";
        $permission->display_name = "User Read";
        $permission->save();

        $permission = new Permission;
        $permission->name = "user_update";
        $permission->display_name = "User Update";
        $permission->save();

        $permission = new Permission;
        $permission->name = "user_delete";
        $permission->display_name = "User Delete";
        $permission->save();
    }
}
