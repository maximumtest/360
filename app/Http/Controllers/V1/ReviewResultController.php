<?php

namespace App\Http\Controllers\V1;

use App\ReviewResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\ReviewResult\CreateReviewResultRequest;
use App\Http\Requests\V1\ReviewResult\UpdateReviewResultRequest;
use Illuminate\Support\Facades\Auth;
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

        $this->authorize('view', $reviewResult);

        return response()->json($reviewResult, 200);
    }

    public function store(CreateReviewResultRequest $request): JsonResponse
    {
        $doesReviewExist = ReviewResult::where('review_id', $request->input('review_id'))
            ->where('respondent_id', $request->input('respondent_id'))
            ->where('interviewer_id', Auth::user()->getAuthIdentifier())
            ->exists();

        if ($doesReviewExist) {
            return response()->json('This respondent is already reviewed', 400);
        }

        $params = $request->validated();
        $params['interviewer_id'] = $params['interviewer_id'] ?? Auth::user()->getAuthIdentifier();

        $reviewResult = new ReviewResult($params);

        $this->authorize('create', $reviewResult);

        $reviewResult->save();

        return response()->json($reviewResult, 201);
    }

    public function update(UpdateReviewResultRequest $request, string $reviewResultId): JsonResponse
    {
        $reviewResult = ReviewResult::findOrFail($reviewResultId);

        $this->authorize('update', $reviewResult);

        $reviewResult->update($request->validated());

        return response()->json($reviewResult, 200);
    }

    public function destroy(string $reviewResultId): JsonResponse
    {
        $reviewResult = ReviewResult::findOrFail($reviewResultId);

        $this->authorize('delete', $reviewResult);

        $reviewResult->delete();

        return response()->json(null, 204);
    }

    public function getReviewResults(string $reviewId): JsonResponse
    {
        $userId = Auth::id();

        $reviewResults = ReviewResult::where('review_id', $reviewId)
            ->where('interviewer_id', $userId)
            ->get();

        return response()->json($reviewResults);
    }
}
