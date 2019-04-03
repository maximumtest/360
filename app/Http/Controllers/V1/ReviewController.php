<?php

namespace App\Http\Controllers\V1;

use App\Review;
use App\Http\Controllers\Controller;
use App\ReviewStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\Review\CreateReviewRequest;
use App\Http\Requests\V1\Review\UpdateReviewRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviewController extends Controller
{
    public function index(): JsonResponse
    {
        $reviews = Review::all();

        if ($reviews->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($reviews, 200);
    }

    public function store(CreateReviewRequest $request): JsonResponse
    {
        $params = array_merge(
            $request->validated(),
            ['review_status_id' => ReviewStatus::STATUS_DRAFT]
        );

        $review = Review::create($params);

        $review->users()->sync($request->input('users'));

        return response()->json($review, 201);
    }

    public function show(string $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        return response()->json($review, 200);
    }

    public function update(UpdateReviewRequest $request, string $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        $review->update($request->validated());

        if ($request->has('users')) {
            $review->users()->sync($request->input('users'));
        }

        return response()->json($review, 200);
    }

    public function destroy(string $id): JsonResponse
    {
        Review::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
