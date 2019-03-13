<?php

use Illuminate\Database\Seeder;
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
        $user = DB::collection('users')->create([
            'name' => 'admin',
            'email' => 'admin@360.ru',
            'password' => Hash::make('Hu75puNgA9GLVqz7aFAbAY5NfJmZhn'),
            'email_verified_at' => now(),
        ]);
        
        $role = Role::where('name', Role::ROLE_ADMIN)->first();

        $user->assignRole($role->_id);
    }
}
