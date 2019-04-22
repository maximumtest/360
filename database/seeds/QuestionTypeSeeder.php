<?php

use Illuminate\Database\Seeder;
use App\QuestionType;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuestionType::firstOrCreate(['name' => QuestionType::TYPE_CHECKBOX]);
        QuestionType::firstOrCreate(['name' => QuestionType::TYPE_RADIO]);
        QuestionType::firstOrCreate(['name' => QuestionType::TYPE_SELECT]);
        QuestionType::firstOrCreate(['name' => QuestionType::TYPE_TEXT]);
        QuestionType::firstOrCreate(['name' => QuestionType::TYPE_TEXTAREA]);
    }
}
