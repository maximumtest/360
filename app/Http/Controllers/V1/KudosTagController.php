<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\KudosTag\CreateKudosTagRequest;
use App\Http\Requests\V1\KudosTag\UpdateKudosTagRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\KudosTag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

class KudosTagController extends Controller
{
    public function index(): JsonResponse
    {
        $kudosTags = KudosTag::all();

        if ($kudosTags->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($kudosTags, 200);
    }

    public function show(string $kudosTagId): JsonResponse
    {
        $kudosTag = KudosTag::findOrFail($kudosTagId);

        return response()->json($kudosTag, 200);
    }

    public function store(CreateKudosTagRequest $request): JsonResponse
    {
        $kudosTag = KudosTag::create($request->validated());

        return response()->json($kudosTag, 201);
    }

    public function update(UpdateKudosTagRequest $request, string $kudosTagId): JsonResponse
    {
        $kudosTag = KudosTag::findOrFail($kudosTagId);

        $kudosTag->update($request->validated());

        return response()->json($kudosTag, 200);
    }

    public function destroy(string $kudosTagId): JsonResponse
    {
        KudosTag::findOrFail($kudosTagId)->delete();

        return response()->json(null, 204);
    }

    public function filter(Request $request): JsonResponse
    {
        $tagName = $request->input('name');

        $kudosTags = KudosTag::where('name', 'like', "%{$tagName}%")->get();

        if ($kudosTags->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($kudosTags, 200);
    }
}
