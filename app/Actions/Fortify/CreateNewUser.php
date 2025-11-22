<?php

namespace App\Actions\Fortify;

use App\Models\FortuneSummary;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'plan' => ['nullable', 'string', 'in:free,subscription'], // プラン選択（P2で実装予定）
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        // セッションに保存された鑑定結果をユーザーに紐づける
        $this->attachFortuneCalculationToUser($user);

        return $user;
    }

    /**
     * セッションに保存された鑑定結果をユーザーに紐づける
     */
    private function attachFortuneCalculationToUser(User $user): void
    {
        $fortuneCalculation = Session::get('fortune_calculation');

        if (!$fortuneCalculation) {
            return;
        }

        // ユーザーのプロフィール情報を更新（生年月日など）
        if (isset($fortuneCalculation['birth_date'])) {
            $user->birth_date = $fortuneCalculation['birth_date'];
        }
        if (isset($fortuneCalculation['birth_time'])) {
            $user->birth_time = $fortuneCalculation['birth_time'];
        }
        if (isset($fortuneCalculation['birth_place'])) {
            $user->birth_place = $fortuneCalculation['birth_place'];
        }
        $user->save();

        // 鑑定結果をfortune_summariesテーブルに保存
        $birthTime = null;
        if (isset($fortuneCalculation['birth_time']) && $fortuneCalculation['birth_time'] !== '不明') {
            $birthTime = $fortuneCalculation['birth_time'];
        }

        FortuneSummary::create([
            'user_id' => $user->id,
            'birth_date' => $fortuneCalculation['birth_date'],
            'birth_time' => $birthTime,
            'birth_place' => $fortuneCalculation['birth_place'] ?? null,
            'four_pillars_result' => $fortuneCalculation['four_pillars'] ?? null,
            'numerology_result' => $fortuneCalculation['numerology'] ?? null,
            'ziwei_result' => $fortuneCalculation['ziwei'] ?? null,
            'tarot_result' => $fortuneCalculation['tarot'] ?? null,
            'calculated_at' => now()->toDateString(),
        ]);

        // セッションから削除
        Session::forget('fortune_calculation');
    }
}
