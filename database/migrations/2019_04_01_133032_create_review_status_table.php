<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\ReviewStatus;

class CreateReviewStatusTable extends Migration
{
    const TABLE_NAME = 'review_statuses';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        DB::table(self::TABLE_NAME)->insert([
            ['name' => ReviewStatus::STATUS_DRAFT],
            ['name' => ReviewStatus::STATUS_IN_PROGRESS],
            ['name' => ReviewStatus::STATUS_PAUSED],
            ['name' => ReviewStatus::STATUS_FINISHED],
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
