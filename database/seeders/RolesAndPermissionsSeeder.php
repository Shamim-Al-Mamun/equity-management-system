<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        Permission::create(['name' => 'manage clients']);
        Permission::create(['name' => 'manage holdings']);
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'view audits']);
        Permission::create(['name' => 'manage schedules']);

        // Roles and assign permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo(['manage clients', 'manage holdings']);

        $analyst = Role::create(['name' => 'analyst']);
        $analyst->givePermissionTo(['view reports']);

        // Create users with known passwords
        $adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);
        $adminUser->assignRole('admin');

        $managerUser = User::factory()->create([
            'email' => 'manager@example.com',
            'password' => bcrypt('manager123'),
        ]);
        $managerUser->assignRole('manager');

        $analystUser = User::factory()->create([
            'email' => 'analyst@example.com',
            'password' => bcrypt('analyst123'),
        ]);
        $analystUser->assignRole('analyst');
    }
}
