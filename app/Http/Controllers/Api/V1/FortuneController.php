<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Fortune\DailyFortuneService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FortuneController extends Controller
{
    public function __construct(
        private DailyFortuneService $dailyFortuneService
    ) {
    }

    /**
     * 今日の運勢を取得
     */
    public function today(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $fortune = $this->dailyFortuneService->calculateToday($user);
        
        return response()->json([
            'success' => true,
            'data' => $fortune,
        ]);
    }
}



