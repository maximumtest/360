<?php

use Illuminate\Database\Seeder;
use App\QuestionType;
use App\Question;
use Faker\Factory;
use App\User;
use App\Template;
use App\Review;

class FilledReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $manager = factory(User::class)->create();

        $radioQuestionType = QuestionType::firstOrCreate(['name' => QuestionType::TYPE_RADIO]);
        $checkboxQuestionType = QuestionType::firstOrCreate(['name' => QuestionType::TYPE_CHECKBOX]);
        $selectQuestionType = QuestionType::firstOrCreate(['name' => QuestionType::TYPE_SELECT]);
        $textQuestionType = QuestionType::firstOrCreate(['name' => QuestionType::TYPE_TEXT]);
        $textareaQuestionType = QuestionType::firstOrCreate(['name' => QuestionType::TYPE_TEXTAREA]);

        $questionRadio = Question::create([
            'question_type_id' => $radioQuestionType->_id,
            'text' => $faker->text(30),
            'answers' => range(1, 4),
            'author_id' => $manager->_id,
        ]);

        $questionCheckbox = Question::create([
            'question_type_id' => $checkboxQuestionType->_id,
            'text' => $faker->text(30),
            'answers' => range(1, 4),
            'author_id' => $manager->_id,
        ]);

        $questionSelect = Question::create([
            'question_type_id' => $selectQuestionType->_id,
            'text' => $faker->text(30),
            'answers' => range(1, 4),
            'author_id' => $manager->_id,
        ]);

        $questionText = Question::create([
            'question_type_id' => $textQuestionType->_id,
            'text' => $faker->text(30),
            'author_id' => $manager->_id,
        ]);

        $questionTextarea = Question::create([
            'question_type_id' => $textareaQuestionType->_id,
            'text' => $faker->text(30),
            'author_id' => $manager->_id,
        ]);

        $template = Template::create([
            'name' => $faker->text(20),
            'author_id' => $manager->_id,
        ]);

        $template->questions()->sync([
            $questionRadio->_id,
            $questionCheckbox->_id,
            $questionSelect->_id,
            $questionText->_id,
            $questionTextarea->_id,
        ]);

        $review = Review::create([
            'template_id' => $template->_id,
            'title' => 'Review Example #' . time(),
        ]);

        $review->users()->sync(User::pluck('_id')->toArray());
    }
}
