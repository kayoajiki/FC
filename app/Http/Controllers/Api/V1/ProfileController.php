<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * ユーザープロフィールを取得
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'birth_date' => $user->birth_date?->format('Y-m-d'),
                'birth_time' => $user->birth_time,
                'birth_place' => $user->birth_place,
                'email_verified_at' => $user->email_verified_at?->toIso8601String(),
                'created_at' => $user->created_at->toIso8601String(),
                'updated_at' => $user->updated_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * ユーザープロフィールを更新
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $validated = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'birth_date' => ['sometimes', 'nullable', 'date', 'before_or_equal:today', 'after_or_equal:1900-01-01'],
            'birth_time' => ['sometimes', 'nullable', 'string'],
            'birth_place' => ['sometimes', 'nullable', 'string', 'max:255'],
        ])->validate();
        
        $user->fill($validated);
        $user->save();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'birth_date' => $user->birth_date?->format('Y-m-d'),
                'birth_time' => $user->birth_time,
                'birth_place' => $user->birth_place,
            ],
            'message' => 'プロフィールを更新しました',
        ]);
    }
}



