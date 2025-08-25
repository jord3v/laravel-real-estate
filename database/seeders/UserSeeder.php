<?php

namespace Database\Seeders;

use App\Models\CentralUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = CentralUser::create([
            'name' => 'Admin Master',
            'email' => 'admin@admin.com',
            'password' => 'secret',
        ]);
        User::find($user->id)->assignRole('master-admin');
    }
}
