<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //syncRoles([role1,role2])
        // assignRole(role)
        $admin = Role::create(["name" => "admin"]);
        $productManager = Role::create(["name" => "productManager"]);
        $collaborator = Role::create(["name" => "collaborator", "guard_name" => "web"]);

        // PM permissions
        $createTaskPermission = Permission::create(['name' => 'create task']);
        $editTaskPermission = Permission::create(['name' => 'edit task']);
        $deleteTaskPermission = Permission::create(['name' => 'delete task']);

        // ADMIN permissions
        $createProjectPermission = Permission::create(['name' => 'create project']);
        $editProjectPermission = Permission::create(['name' => 'edit project']);
        $deleteProjectPermission = Permission::create(['name' => 'delete project']);

        // Set permissions to roles
        $admin->givePermissionTo($createProjectPermission, $editProjectPermission, $deleteProjectPermission);
        $productManager->givePermissionTo($createTaskPermission, $editTaskPermission, $deleteTaskPermission);
        $collaborator->givePermissionTo($createTaskPermission, $editTaskPermission, $deleteTaskPermission);

        User::create([
            'name' => 'humberto',
            'email' => 'humbertodev14@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole($admin);
        User::create([
            'name' => 'leo',
            'email' => 'leo@gmail.com',
            'password' => bcrypt('12345678')
        ])->assignRole($productManager);
        User::create([
            'name' => 'messi',
            'email' => 'messi@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        User::create([
            'name' => 'cristiano',
            'email' => 'cristiano@gmail.com',
            'password' => bcrypt('12345678')
        ]);
    }
}
