<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Seed roles and permissions and attach them to users.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Roles & permissions management
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
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // Medicines
            'view medicines',
            'create medicines',
            'edit medicines',
            'delete medicines',

            // Stock movements
            'view stock-movements',
            'create stock-movements',
            'edit stock-movements',
            'delete stock-movements',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $pharmacist = Role::firstOrCreate(['name' => 'pharmacist', 'guard_name' => 'web']);
        $inventoryManager = Role::firstOrCreate(['name' => 'inventory-manager', 'guard_name' => 'web']);

        $superAdmin->syncPermissions(Permission::all());

        $pharmacist->syncPermissions([
            'view users',
            'view categories',
            'view medicines',
            'create medicines',
            'edit medicines',
            'view stock-movements',
            'create stock-movements',
        ]);

        $inventoryManager->syncPermissions([
            'view users',
            'view categories',
            'view medicines',
            'edit medicines',
            'view stock-movements',
            'create stock-movements',
            'edit stock-movements',
            'delete stock-movements',
        ]);

        $adminUser = User::query()
            ->where('email', 'admin@admin.com')
            ->first();

        if (! $adminUser) {
            $adminUser = User::query()->first();
        }

        if ($adminUser) {
            $adminUser->assignRole('super-admin');
        }
    }
}
