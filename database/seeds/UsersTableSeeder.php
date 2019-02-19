<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::collection('users')->insert([
            'name' => 'admin',
            'email' => 'admin@360.ru',
            'password' => Hash::make('Hu75puNgA9GLVqz7aFAbAY5NfJmZhn'),
            'email_verified_at' => now(),
        ]);
        
        $role = Role::where('name', Role::ROLE_ADMIN)->first();

        $admin = User::where('email', 'admin@360.ru')->first();
        
        $admin->roles()->attach($role->id);
    }
}
