<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\Hash;

class BasicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Create administrator user
        $user = User::where('email', 'admin@mail.com')->first();

        if (empty($user)) {

            $user = new User;

            $user->updated_by = Auth::id();
            $user->name = 'Administrator';
            $user->email = 'admin@mail.com';
            $user->address = 'Kendal';
            $user->password = Hash::make('123456');
            $user->photo = null;
            $user->save();
        }

//        Create administrator role
        $role = Role::where('name', 'administrator')->first();

        if (empty($role)) {
            $role = new Role();
            $role->name = 'administrator';
            $role->description = 'administrator';
            $role->save();
        }

//        Add role to administrator user
        $user->roles()->attach($role->id);

        $permission = Permission::where('name', 'users_create')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "users_create";
            $permission->description = "Users Create";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

        $permission = Permission::where('name', 'users_read')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "users_read";
            $permission->description = "Users Read";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

        $permission = Permission::where('name', 'users_update')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "users_update";
            $permission->description = "Users Update";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

        $permission = Permission::where('name', 'users_delete')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "users_delete";
            $permission->description = "Users Delete";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }
//

        $permission = Permission::where('name', 'roles_create')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "roles_create";
            $permission->description = "Roles Create";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

        $permission = Permission::where('name', 'roles_read')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "roles_read";
            $permission->description = "Roles Read";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

        $permission = Permission::where('name', 'roles_update')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "roles_update";
            $permission->description = "Roles Update";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

        $permission = Permission::where('name', 'roles_delete')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "roles_delete";
            $permission->description = "Roles Delete";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

//

        $permission = Permission::where('name', 'permissions_create')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "permissions_create";
            $permission->description = "Permissions Create";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

        $permission = Permission::where('name', 'permissions_read')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "permissions_read";
            $permission->description = "Permissions Read";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

        $permission = Permission::where('name', 'permissions_update')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "permissions_update";
            $permission->description = "Permissions Update";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }

        $permission = Permission::where('name', 'permissions_delete')->first();

        if (empty($permission)) {
            $permission = new Permission;
            $permission->name = "permissions_delete";
            $permission->description = "Permissions Delete";
            $permission->save();

            $role->permissions()->attach($permission->id);
        }
    }
}
