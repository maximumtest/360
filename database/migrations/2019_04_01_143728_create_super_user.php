<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;

class CreateSuperUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $adminRole = Role::firstOrCreate([
            'name' => Role::ROLE_ADMIN,
        ]);

        $superUser = User::firstOrCreate([
            'name' => 'admin',
            'email' => 'admin',

        ]);

        $superUser->update([
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
        ]);

        $superUser->assignRole($adminRole);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::where('email', 'admin')->firstOrFail()->delete();
    }
}
