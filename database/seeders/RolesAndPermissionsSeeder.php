<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // تنظيف الكاش
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Permissions (مقسمة بشكل احترافي)
        |--------------------------------------------------------------------------
        */
        $permissions = [

            // Roles & Permissions
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',

            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Categories
            // 'view categories',
            // 'create categories',
            // 'edit categories',
            // 'delete categories',

            // Medicines
            // 'view medicines',
            // 'create medicines',
            // 'edit medicines',
            // 'delete medicines',

            // department
            'view department',
            'create department',
            'edit department',
            'delete department',
        ];

        // إنشاء Permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        $roles = [
            'super-admin',
            'admin',
            'pharmacist',
            'inventory-manager',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Assign Permissions to Roles
        |--------------------------------------------------------------------------
        */

        // Super Admin -> كل شيء
        Role::findByName('super-admin')->syncPermissions(Permission::all());

        // Admin
        Role::findByName('admin')->syncPermissions([
            'view users',
            'create users',
            'edit users',
            'delete users',
        ]);

        // Pharmacist
        Role::findByName('pharmacist')->syncPermissions([
            'view medicines',
            'create medicines',
            'edit medicines',
            'view stock',
            'create stock',
        ]);

        // Inventory Manager
        Role::findByName('inventory-manager')->syncPermissions([
            'view stock',
            'create stock',
            'edit stock',
            'delete stock',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Assign Role to User
        |--------------------------------------------------------------------------
        */
        $user = User::where('email', 'admin@admin.com')->first();

        if (! $user) {
            $user = User::first();
        }

        if ($user) {
            $user->assignRole('super-admin');
        }
    }
}
