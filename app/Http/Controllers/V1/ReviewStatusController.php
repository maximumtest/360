<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\ReviewStatus;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReviewStatusController extends Controller
{
    public function getAll(): JsonResponse
    {
        $reviewStatuses = ReviewStatus::all();

        if ($reviewStatuses->count() === 0) {
            throw new NotFoundHttpException();
        }

        return response()->json($reviewStatuses, 200);
    }
}
