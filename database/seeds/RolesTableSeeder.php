<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate(['name' => Role::ROLE_EMPLOYEE]);
        Role::firstOrCreate(['name' => Role::ROLE_MANAGER]);
        Role::firstOrCreate(['name' => Role::ROLE_ADMIN]);
    }
}
