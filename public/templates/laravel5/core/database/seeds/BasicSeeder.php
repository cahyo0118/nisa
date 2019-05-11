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
        $user = new User;

        $user->updated_by = Auth::id();
        $user->name = 'Administrator';
        $user->email = 'admin@mail.com';
        $user->address = 'Kendal';
        $user->password = Hash::make('123456');
        $user->photo = null;
        $user->save();

//        Create administrator role
        $role = new Role();
        $role->name = 'administrator';
        $role->description = 'administrator';
        $role->save();

//        Add role to administrator user
        $user->roles()->attach($role->id);

        $permission = new Permission;
        $permission->name = "users_create";
        $permission->description = "Users Create";
        $permission->save();

        $role->permissions()->attach($permission->id);

        $permission = new Permission;
        $permission->name = "users_read";
        $permission->description = "Users Read";
        $permission->save();

        $role->permissions()->attach($permission->id);

        $permission = new Permission;
        $permission->name = "users_update";
        $permission->description = "Users Update";
        $permission->save();

        $role->permissions()->attach($permission->id);

        $permission = new Permission;
        $permission->name = "users_delete";
        $permission->description = "Users Delete";
        $permission->save();

        $role->permissions()->attach($permission->id);
    }
}
