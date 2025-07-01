<?php

namespace Database\Seeders;

use App\Models\HwaPermission;
use App\Models\HwaRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guard_name = 'admin';

        $timestamp = [
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $adminRole = [
            'id' => 1,
            'name' => 'super_admin',
            'guard_name' => $guard_name,
            'title' => 'Quản trị hệ thống',
        ];

        // Create role super admin
        HwaRole::updateOrCreate([
            'id' => $adminRole['id'],
            'name' => $adminRole['name'],
        ], array_merge($adminRole, $timestamp));

        $permissions = hwaCore()->getPermissions() ?? []; // Get permissions
        foreach ($permissions as $permission) {
            // Create permission
            HwaPermission::updateOrCreate([
                'name' => $permission,
            ], array_merge([
                'name' => $permission,
                'guard_name' => $guard_name
            ], $timestamp));
        }

        // Assign full permission for super admin
        $role = Role::whereName($adminRole['name'])->first();
        $role->syncPermissions($permissions);
    }

}
