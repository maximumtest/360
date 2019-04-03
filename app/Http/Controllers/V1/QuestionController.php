<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Question;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\V1\Question\CreateQuestionRequest;
use App\Http\Requests\V1\Question\UpdateQuestionRequest;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(): JsonResponse
    {
        $questions = Question::all();

        if ($questions->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($questions);
    }

    public function show(string $questionId): JsonResponse
    {
        $question = Question::findOrFail($questionId);

        return response()->json($question);
    }

    public function store(CreateQuestionRequest $request): JsonResponse
    {
        Question::create($request->validated());

        return response()->json(null, 201);
    }

    public function update(UpdateQuestionRequest $request, string $questionId): JsonResponse
    {
        Question::findOrFail($questionId)->update($request->validated());

        return response()->json(null, 204);
    }

    public function destroy(string $questionId): JsonResponse
    {
        Question::findOrFail($questionId)->delete();

        return response()->json(null, 204);
    }

    public function filter(Request $request): JsonResponse
    {
        $questionText = $request->input('text');

        $questions = Question::where('text', 'like', "%{$questionText}%")->get();

        if ($questions->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($questions);
    }
}
