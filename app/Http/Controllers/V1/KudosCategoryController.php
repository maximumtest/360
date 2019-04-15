<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\KudosCategory\CreateKudosCategoryRequest;
use App\Http\Requests\V1\KudosCategory\UpdateKudosCategoryRequest;
use Illuminate\Http\JsonResponse;
use App\KudosCategory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Controllers\Controller;

class KudosCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $kudosCategories = KudosCategory::all();

        if ($kudosCategories->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($kudosCategories, 200);
    }

    public function show(string $kudosCategoryId): JsonResponse
    {
        $kudosCategory = KudosCategory::findOrFail($kudosCategoryId);

        return response()->json($kudosCategory, 200);
    }

    public function store(CreateKudosCategoryRequest $request): JsonResponse
    {
        $kudosCategory = KudosCategory::create($request->validated());

        return response()->json($kudosCategory, 201);
    }

    public function update(UpdateKudosCategoryRequest $request, string $kudosCategoryId): JsonResponse
    {
        $kudosCategory = KudosCategory::findOrFail($kudosCategoryId);

        $kudosCategory->update($request->validated());

        return response()->json($kudosCategory, 200);
    }

    public function destroy(string $kudosCategoryId): JsonResponse
    {
        KudosCategory::findOrFail($kudosCategoryId)->delete();

        return response()->json(null, 204);
    }
}
