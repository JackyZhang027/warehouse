<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',
           'warehouse-list',
           'warehouse-create',
           'warehouse-edit',
           'warehouse-delete',
           'uom-list',
           'uom-create',
           'uom-edit',
           'uom-delete',
           'category-list',
           'category-create',
           'category-edit',
           'category-delete',
           'item-list',
           'item-create',
           'item-edit',
           'item-delete',
        ];
        
        foreach ($permissions as $permission) {
             Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
