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
        DB::collection('roles')->insert(['name' => Role::ROLE_EMPLOYEE]);
        DB::collection('roles')->insert(['name' => Role::ROLE_MANAGER]);
        DB::collection('roles')->insert(['name' => Role::ROLE_ADMIN]);
    }
}
