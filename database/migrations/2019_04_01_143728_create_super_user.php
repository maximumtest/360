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
        $adminRole = Role::where([
            'name' => Role::ROLE_ADMIN,
        ])->get();

        $superUser = User::create([
            'name' => 'admin',
            'email' => 'admin',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
        ]);

        $superUser->assignRole($adminRole->id);
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
