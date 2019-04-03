<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\QuestionType;

class CreateQuestionTypeTable extends Migration
{
    const TABLE_NAME = 'question_types';

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
            ['name' => QuestionType::TYPE_RADIO],
            ['name' => QuestionType::TYPE_CHECKBOX],
            ['name' => QuestionType::TYPE_SELECT],
            ['name' => QuestionType::TYPE_TEXT],
            ['name' => QuestionType::TYPE_TEXTAREA],
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
