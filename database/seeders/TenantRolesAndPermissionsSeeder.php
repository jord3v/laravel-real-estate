<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TenantRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpar o cache de permissões e papéis
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Criar papéis para a aplicação do inquilino
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'customer']);

        // 2. Criar permissões para a aplicação do inquilino
        $permissions = [
            'manage-properties',
            'manage-users',
            'view-dashboard',
            'submit-requests',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Atribuir permissões aos papéis
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo(Permission::all());
        
        $customerRole = Role::findByName('customer');
        $customerRole->givePermissionTo(['view-dashboard', 'submit-requests']);
    }
}
