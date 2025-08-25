<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CentralRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpar o cache de permissões e papéis
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Criar papéis para a aplicação central
        Role::firstOrCreate(['name' => 'master-admin']);
        Role::firstOrCreate(['name' => 'tenant']);

        // 2. Criar permissões de alto nível
        Permission::firstOrCreate(['name' => 'manage-platform']);

        // 3. Atribuir a permissão ao master-admin
        $masterAdminRole = Role::findByName('master-admin');
        $masterAdminRole->givePermissionTo('manage-platform');
    }
}