<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Fortune\TarotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TarotController extends Controller
{
    public function __construct(
        private TarotService $tarotService
    ) {
    }

    /**
     * タロットカードを1枚引く
     */
    public function draw(Request $request): JsonResponse
    {
        $includeReversed = $request->boolean('include_reversed', true);
        
        $card = $this->tarotService->drawOne($includeReversed);
        
        // ログに記録（オプション）
        if ($request->user()) {
            \App\Models\TarotLog::create([
                'user_id' => $request->user()->id,
                'card_name' => $card['card_name'],
                'position' => $card['position'],
                'message' => $card['message'],
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $card,
        ]);
    }
}



