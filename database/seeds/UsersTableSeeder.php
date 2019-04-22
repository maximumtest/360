<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::firstOrCreate(['name' => Role::ROLE_ADMIN]);

        $adminUser = User::firstOrCreate([
            'name' => 'admin',
            'email' => 'admin@360.ru',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
        ]);

        $adminUser->assignRole($adminRole);
    }
}
