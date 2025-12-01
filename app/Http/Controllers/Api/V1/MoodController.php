<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Mood;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoodController extends Controller
{
    /**
     * 感情ログ一覧を取得
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $moods = Mood::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(30);
        
        return response()->json([
            'success' => true,
            'data' => $moods,
        ]);
    }

    /**
     * 感情ログを保存
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $validated = Validator::make($request->all(), [
            'date' => ['required', 'date'],
            'mood_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'mood_emoji' => ['nullable', 'string', 'max:10'],
            'memo' => ['nullable', 'string', 'max:500'],
        ])->validate();
        
        $mood = Mood::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $validated['date'],
            ],
            [
                'mood_rating' => $validated['mood_rating'],
                'mood_emoji' => $validated['mood_emoji'] ?? null,
                'memo' => $validated['memo'] ?? null,
            ]
        );
        
        return response()->json([
            'success' => true,
            'data' => $mood,
            'message' => '感情ログを保存しました',
        ], 201);
    }

    /**
     * 特定の日の感情ログを取得
     */
    public function show(Request $request, string $date): JsonResponse
    {
        $user = $request->user();
        
        $mood = Mood::where('user_id', $user->id)
            ->where('date', $date)
            ->first();
        
        if (!$mood) {
            return response()->json([
                'success' => false,
                'message' => '感情ログが見つかりません',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $mood,
        ]);
    }

    /**
     * 感情ログを更新
     */
    public function update(Request $request, Mood $mood): JsonResponse
    {
        $user = $request->user();
        
        if ($mood->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => '権限がありません',
            ], 403);
        }
        
        $validated = Validator::make($request->all(), [
            'mood_rating' => ['sometimes', 'required', 'integer', 'min:1', 'max:5'],
            'mood_emoji' => ['nullable', 'string', 'max:10'],
            'memo' => ['nullable', 'string', 'max:500'],
        ])->validate();
        
        $mood->update($validated);
        
        return response()->json([
            'success' => true,
            'data' => $mood,
            'message' => '感情ログを更新しました',
        ]);
    }

    /**
     * 感情ログを削除
     */
    public function destroy(Request $request, Mood $mood): JsonResponse
    {
        $user = $request->user();
        
        if ($mood->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => '権限がありません',
            ], 403);
        }
        
        $mood->delete();
        
        return response()->json([
            'success' => true,
            'message' => '感情ログを削除しました',
        ]);
    }
}



