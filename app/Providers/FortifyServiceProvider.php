<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Models\FortuneSummary;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
        $this->configureLoginEvents();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('livewire.auth.login'));
        Fortify::verifyEmailView(fn () => view('livewire.auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('livewire.auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('livewire.auth.confirm-password'));
        Fortify::registerView(fn () => view('livewire.auth.register'));
        Fortify::resetPasswordView(fn () => view('livewire.auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('livewire.auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }

    /**
     * Configure login events.
     */
    private function configureLoginEvents(): void
    {
        Event::listen(Authenticated::class, function (Authenticated $event) {
            $user = $event->user;
            $fortuneCalculation = Session::get('fortune_calculation');

            if (!$fortuneCalculation) {
                return;
            }

            // ユーザーのプロフィール情報を更新（生年月日など）
            if (isset($fortuneCalculation['birth_date'])) {
                $user->birth_date = $fortuneCalculation['birth_date'];
            }
            if (isset($fortuneCalculation['birth_time']) && $fortuneCalculation['birth_time'] !== '不明') {
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
        });
    }
}
