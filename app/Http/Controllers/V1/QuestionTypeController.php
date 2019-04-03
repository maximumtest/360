<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\QuestionType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionTypeController extends Controller
{
    public function getAll(): JsonResponse
    {
        $questionTypes = QuestionType::all();

        if ($questionTypes->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($questionTypes);
    }
}
