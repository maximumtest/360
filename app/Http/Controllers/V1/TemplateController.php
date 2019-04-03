<?php

namespace App\Http\Controllers\V1;

use App\Template;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\V1\Template\CreateTemplateRequest;
use App\Http\Requests\V1\Template\UpdateTemplateRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TemplateController extends Controller
{
    public function index(): JsonResponse
    {
        $templates = Template::all();

        if ($templates->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($templates, 200);
    }

    public function store(CreateTemplateRequest $request): JsonResponse
    {
        $template = Template::create($request->validated());

        $template->questions()->sync($request->input('questions'));

        return response()->json($template, 201);
    }

    public function show(string $id): JsonResponse
    {
        $template = Template::findOrFail($id);

        return response()->json($template, 200);
    }

    public function update(UpdateTemplateRequest $request, string $id): JsonResponse
    {
        $template = Template::findOrFail($id);

        $template->update($request->validated());

        if ($request->has('questions')) {
            $template->questions()->sync($request->input('questions'));
        }

        return response()->json($template, 200);
    }

    public function destroy(string $id): JsonResponse
    {
        Template::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}
