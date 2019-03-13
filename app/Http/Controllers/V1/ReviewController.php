<?php

namespace App\Http\Controllers\V1;

use App\Review;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\Review\CreateReviewRequest;
use App\Http\Requests\V1\Review\UpdateReviewRequest;

class ReviewController extends Controller
{
    public function index(): JsonResponse
    {
        $reviews = Review::all();

        return response()->json($reviews);
    }

    public function store(CreateReviewRequest $request): JsonResponse
    {
        $review = Review::create($request->validated());

        return response()->json($review, 201);
    }

    public function show(string $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        return response()->json($review);
    }

    public function update(UpdateReviewRequest $request, string $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        $review->fill($request->validated());
        $review->save();

        return response()->json($review);
    }

    public function destroy(string $id): JsonResponse
    {
        Review::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
