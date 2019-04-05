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
        $this->authorize('view');
        
        $reviewResults = ReviewResult::all();

        if ($reviewResults->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($reviewResults, 200);
    }

    public function show(string $reviewResultId): JsonResponse
    {
        $this->authorize('view');
        
        $reviewResult = ReviewResult::findOrFail($reviewResultId);

        return response()->json($reviewResult, 200);
    }

    public function store(CreateReviewResultRequest $request): JsonResponse
    {
        $this->authorize('create');
        
        $params = $request->validated();
        
        $interviewer_id = isset($params['interviewer_id']) ? $params['interviewer_id'] : Auth::user()->getAuthIdentifier();
        $params['interviewer_id'] = $interviewer_id;
        
        $reviewResult = ReviewResult::create($params);

        return response()->json($reviewResult, 201);
    }

    public function update(UpdateReviewResultRequest $request, string $reviewResultId): JsonResponse
    {
        $this->authorize('update');
        
        $reviewResult = ReviewResult::findOrFail($reviewResultId);

        $reviewResult->update($request->validated());

        return response()->json(null, 200);
    }

    public function destroy(string $reviewResultId): JsonResponse
    {
        $this->authorize('delete');
        
        ReviewResult::findOrFail($reviewResultId)->delete();

        return response()->json(null, 204);
    }
}
