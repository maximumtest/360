<?php

namespace App\Http\Controllers\V1;

use App\Template;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\Template\CreateTemplateRequest;
use App\Http\Requests\V1\Template\UpdateTemplateRequest;

class TemplateController extends Controller
{
    public function index(): JsonResponse
    {
        $reviews = Template::all();

        return response()->json($reviews);
    }

    public function store(CreateTemplateRequest $request): JsonResponse
    {
        $review = Template::create($request->validated());

        return response()->json($review, 201);
    }

    public function show(string $id): JsonResponse
    {
        $review = Template::findOrFail($id);

        return response()->json($review);
    }

    public function update(UpdateTemplateRequest $request, string $id): JsonResponse
    {
        $review = Template::findOrFail($id);

        $review->fill($request->validated());
        $review->save();

        return response()->json($review);
    }

    public function destroy(string $id): JsonResponse
    {
        Template::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
