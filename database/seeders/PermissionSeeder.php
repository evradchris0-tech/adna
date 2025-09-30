<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::create(['name' => 'manage_users', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_reports', 'guard_name' => 'web']);
    }
}
