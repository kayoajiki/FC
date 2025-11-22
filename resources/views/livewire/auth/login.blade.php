<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('ログイン')" :description="__('メールアドレスとパスワードを入力してください')" />

        <!-- 診断結果がある場合の案内 -->
        @if(session('fortune_calculation'))
            <div class="bg-[#FFFDF9]/80 dark:bg-[#2A2E47]/80 border border-[#F8A38A]/30 dark:border-[#E985A6]/30 rounded-lg p-4 text-sm" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif;">
                <p class="font-medium mb-2">✨ 診断結果を保存します</p>
                <p style="color: rgba(42, 46, 71, 0.7);">ログインまたは新規登録すると、先ほどの診断結果を保存し、いつでも確認できるようになります。</p>
            </div>
        @endif

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('メールアドレス')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('パスワード')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate style="color: #F8A38A;">
                        {{ __('パスワードを忘れた場合') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('ログイン状態を保持する')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button" style="background-color: #2A2E47; color: #FFFDF9; font-family: 'Noto Sans JP', sans-serif;">
                    {{ __('ログイン') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
                <span>{{ __('アカウントをお持ちでない方') }}</span>
                <flux:link :href="route('register')" wire:navigate style="color: #F8A38A;">新規登録</flux:link>
            </div>
        @endif
    </div>
</x-layouts.auth>
