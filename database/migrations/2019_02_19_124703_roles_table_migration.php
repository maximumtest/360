<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Role;

class RolesTableMigration extends Migration
{
    const TABLE_NAME = 'roles';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function ($collection) {
           $collection->unique('name');
        });

        DB::table(self::TABLE_NAME)->insert([
            ['name' => Role::ROLE_EMPLOYEE],
            ['name' => Role::ROLE_MANAGER],
            ['name' => Role::ROLE_ADMIN],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
}
