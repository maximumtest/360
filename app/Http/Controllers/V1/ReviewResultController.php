<?php

namespace App\Http\Controllers\V1;

use App\ReviewResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\ReviewResult\CreateReviewResultRequest;
use App\Http\Requests\V1\ReviewResult\UpdateReviewResultRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviewResultController extends Controller
{
    public function index(): JsonResponse
    {
        $reviewResults = ReviewResult::all();

        if ($reviewResults->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($reviewResults, 200);
    }

    public function show(string $reviewResultId): JsonResponse
    {
        $reviewResult = ReviewResult::findOrFail($reviewResultId);

        return response()->json($reviewResult, 200);
    }

    public function store(CreateReviewResultRequest $request): JsonResponse
    {
        $reviewResult = ReviewResult::create($request->validated());

        return response()->json($reviewResult, 201);
    }

    public function update(UpdateReviewResultRequest $request, string $reviewResultId): JsonResponse
    {
        $reviewResult = ReviewResult::findOrFail($reviewResultId);

        $reviewResult->update($request->validated());

        return response()->json(null, 204);
    }

    public function destroy(string $reviewResultId): JsonResponse
    {
        ReviewResult::findOrFail($reviewResultId)->delete();

        return response()->json(null, 204);
    }
}
