<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $plan = 'free'; // 'free' or 'subscription'
}; ?>

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('アカウントを作成')" :description="__('Fortune Compassの世界へようこそ')" />

        <!-- 診断結果がある場合の案内 -->
        @if(session('fortune_calculation'))
            <div class="bg-[#FFFDF9]/80 dark:bg-[#2A2E47]/80 border border-[#F8A38A]/30 dark:border-[#E985A6]/30 rounded-lg p-4 text-sm" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif;">
                <p class="font-medium mb-2">✨ 診断結果を保存します</p>
                <p style="color: rgba(42, 46, 71, 0.7);">無料登録すると、先ほどの診断結果を保存し、いつでも確認できるようになります。</p>
            </div>
        @endif

        <!-- プラン選択 -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold" style="color: #2A2E47; font-family: 'Noto Sans JP', sans-serif; font-weight: 600;">プランを選択してください</h3>
            
            <div class="grid gap-4">
                <!-- 無料プラン -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="plan" value="free" wire:model="plan" class="sr-only peer" checked>
                    <div class="bg-white/90 dark:bg-[#2A2E47]/90 border-2 border-[#F8A38A]/30 dark:border-[#E985A6]/30 rounded-lg p-6 peer-checked:border-[#F8A38A] dark:peer-checked:border-[#E985A6] peer-checked:shadow-lg transition-all" style="font-family: 'Noto Sans JP', sans-serif;">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="text-lg font-semibold" style="color: #2A2E47; font-weight: 600;">無料プラン</h4>
                                <p class="text-sm mt-1" style="color: rgba(42, 46, 71, 0.7);">メールアドレスのみでOK</p>
                            </div>
                            <span class="text-2xl font-bold" style="color: #2A2E47;">¥0</span>
                        </div>
                        <ul class="space-y-2 text-sm" style="color: rgba(42, 46, 71, 0.8);">
                            <li class="flex items-center gap-2">
                                <span class="text-[#F9C97D]">✓</span>
                                <span>今日の運勢（Daily Light）</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#F9C97D]">✓</span>
                                <span>感情ログ（Mood Record）</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#F9C97D]">✓</span>
                                <span>My命式（簡易版）</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#F9C97D]">✓</span>
                                <span>タロット1枚引き（制限なし）</span>
                            </li>
                        </ul>
                    </div>
                </label>

                <!-- サブスクリプションプラン -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="plan" value="subscription" wire:model="plan" class="sr-only peer">
                    <div class="bg-white/90 dark:bg-[#2A2E47]/90 border-2 border-[#F9C97D]/30 dark:border-[#F9C97D]/30 rounded-lg p-6 peer-checked:border-[#F9C97D] dark:peer-checked:border-[#F9C97D] peer-checked:shadow-lg transition-all" style="font-family: 'Noto Sans JP', sans-serif;">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="text-lg font-semibold" style="color: #2A2E47; font-weight: 600;">プレミアムプラン</h4>
                                <p class="text-sm mt-1" style="color: rgba(42, 46, 71, 0.7);">さらに深く知りたい方へ</p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold" style="color: #F9C97D;">¥980</span>
                                <span class="text-sm" style="color: rgba(42, 46, 71, 0.7);">/月</span>
                            </div>
                        </div>
                        <div class="mb-3 px-3 py-2 bg-[#F9C97D]/20 rounded text-xs font-medium" style="color: #2A2E47;">
                            🎁 初月無料体験期間あり
                        </div>
                        <ul class="space-y-2 text-sm" style="color: rgba(42, 46, 71, 0.8);">
                            <li class="flex items-center gap-2">
                                <span class="text-[#F9C97D]">✓</span>
                                <span>無料プランのすべての機能</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#F9C97D]">✓</span>
                                <span>AI伴走（気持ちの整理サポート）</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#F9C97D]">✓</span>
                                <span>詳細運勢（月運・年運・バイオリズム）</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#F9C97D]">✓</span>
                                <span>My命式（詳細版）</span>
                            </li>
                        </ul>
                    </div>
                </label>
            </div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6" id="register-form">
            @csrf
            <input type="hidden" name="plan" value="free" id="plan-input">

            <!-- Name -->
            <flux:input
                name="name"
                :label="__('お名前')"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="山田 太郎"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('メールアドレス')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('パスワード')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('パスワード（確認）')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button" id="submit-button" style="background-color: #2A2E47; color: #FFFDF9; font-family: 'Noto Sans JP', sans-serif;">
                    無料でアカウントを作成
                </flux:button>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const planInputs = document.querySelectorAll('input[name="plan"]');
                const planHiddenInput = document.getElementById('plan-input');
                const submitButton = document.getElementById('submit-button');

                function updatePlan() {
                    const selectedPlan = document.querySelector('input[name="plan"]:checked')?.value || 'free';
                    planHiddenInput.value = selectedPlan;
                    
                    if (selectedPlan === 'subscription') {
                        submitButton.textContent = 'プレミアムプランで始める';
                    } else {
                        submitButton.textContent = '無料でアカウントを作成';
                    }
                }

                planInputs.forEach(input => {
                    input.addEventListener('change', updatePlan);
                });

                updatePlan();
            });
        </script>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm" style="color: rgba(42, 46, 71, 0.7); font-family: 'Noto Sans JP', sans-serif;">
            <span>すでにアカウントをお持ちですか？</span>
            <flux:link :href="route('login')" wire:navigate style="color: #F8A38A;">ログイン</flux:link>
        </div>
    </div>
</x-layouts.auth>
