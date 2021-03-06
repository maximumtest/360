<?php

namespace App\Http\Controllers\V1;

use App\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Kudos\CreateKudosRequest;
use App\Http\Requests\V1\Kudos\UpdateKudosRequest;
use App\Kudos;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

class KudosController extends Controller
{
    public function index(User $userTo): JsonResponse
    {
        $kudos = Kudos::with(['userFrom', 'kudosCategory', 'kudosTags'])
            ->where('user_to_id', $userTo->id)
            ->latest()
            ->get();

        if ($kudos->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($kudos, 200);
    }

    public function show(User $userTo, string $kudosId): JsonResponse
    {
        $kudos = Kudos::findOrFail($kudosId);

        return response()->json($kudos, 200);
    }

    public function store(CreateKudosRequest $request, User $userTo): JsonResponse
    {
        $this->authorize('store', Kudos::class);

        if ($userTo->id === Auth::id()) {
            throw new Exception('You can\'t assign kudos for yourself', 400);
        }

        $kudos = new Kudos($request->validated());
        $kudos->user_to_id = $userTo->id;
        $kudos->save();

        $kudos->syncTags($request->input('tags', []));

        return response()->json($kudos, 201);
    }

    public function update(UpdateKudosRequest $request, User $userTo, string $kudosId): JsonResponse
    {
        $kudos = Kudos::findOrFail($kudosId);

        $this->authorize('update', $kudos);

        $kudos->update($request->validated());

        $kudos->syncTags($request->input('tags', []));

        return response()->json($kudos, 200);
    }

    public function destroy(User $userTo, string $kudosId): JsonResponse
    {
        $kudos = Kudos::findOrFail($kudosId);

        $this->authorize('delete', $kudos);

        $kudos->delete();

        return response()->json(null, 204);
    }
}
