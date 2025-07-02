<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $user = User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
        ]);

        $roleName = config('filament-shield.super_admin.name', 'super_admin');
        $guard = 'web';

        $role = Role::firstOrCreate([
            'name' => $roleName,
            'guard_name' => $guard,
        ]);

        $permissions = [
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
            'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role',
            'view_any_permission', 'view_permission', 'create_permission', 'update_permission', 'delete_permission',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => $guard,
            ]);
        }

        $role->syncPermissions(Permission::all());

        $user->assignRole($role);
    }
    
}

