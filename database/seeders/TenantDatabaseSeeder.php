<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Database\Seeders\Central\UserSeeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TenantRolesAndPermissionsSeeder::class,
            TypeSeeder::class,
            //PropertySeeder::class
        ]);
    }
}
