<?php

use Livewire\Volt\Component;

new class extends Component {
    public ?string $birth_date = null;
    public ?string $birth_time = null;
    public ?string $birth_place = null;
    public bool $birthDateLocked = false;

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->birth_date) {
            $this->birth_date = $user->birth_date->format('Y-m-d');
        }

        $this->birthDateLocked = (bool) $user->birth_date_finalized_at;

        $this->birth_time = $user->birth_time;
        $this->birth_place = $user->birth_place;
    }

    public function save(): void
    {
        $user = auth()->user();

        $rules = [
            'birth_time' => ['required', 'date_format:H:i'],
            'birth_place' => ['required', 'string', 'max:255'],
        ];

        if (!$this->birthDateLocked) {
            $rules['birth_date'] = ['required', 'date', 'before_or_equal:today'];
        }

        $validated = $this->validate($rules, [], [
            'birth_date' => '生年月日',
            'birth_time' => '出生時刻',
            'birth_place' => '出生地',
        ]);

        if (!$this->birthDateLocked && isset($validated['birth_date'])) {
            $user->birth_date = $validated['birth_date'];
            $user->birth_date_finalized_at = now();
            $this->birth_date = $validated['birth_date'];
            $this->birthDateLocked = true;
        }

        $user->birth_time = $validated['birth_time'];
        $user->birth_place = $validated['birth_place'];
        $user->save();

        session()->flash('status', '出生情報を保存しました。');

        $this->redirectIntended(route('dashboard'), navigate: true);
    }
}; ?>

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('出生情報の登録')" :description="__('正確な鑑定のために、生年月日・出生時刻・出生地を教えてください。')" />

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white/90 dark:bg-[#2A2E47]/90 border border-[#F8A38A]/30 dark:border-[#E985A6]/30 rounded-2xl p-6 space-y-4" style="font-family: 'Noto Sans JP', sans-serif;">
            <h3 class="text-lg font-semibold text-[#2A2E47] dark:text-[#FFFDF9]">出生情報</h3>
            <p class="text-sm text-[#2A2E47]/70 dark:text-[#FFFDF9]/70">
                生年月日はこの画面で確定すると変更できません。出生時刻・出生地は後からでも調整できます。
            </p>

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="space-y-2">
                    <flux:input
                        name="birth_date"
                        :label="__('生年月日')"
                        type="date"
                        wire:model="birth_date"
                        :disabled="$birthDateLocked"
                        required
                    />
                    @if ($birthDateLocked)
                        <p class="text-xs text-[#2A2E47]/60 dark:text-[#FFFDF9]/60">
                            生年月日は確定済みのため変更できません。
                        </p>
                    @else
                        <p class="text-xs text-[#2A2E47]/60 dark:text-[#FFFDF9]/60">
                            生年月日はここで最終決定されます。内容をご確認ください。
                        </p>
                    @endif
                    @error('birth_date')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <flux:input
                            name="birth_time"
                            :label="__('出生時刻（24時間表記）')"
                            type="time"
                            wire:model="birth_time"
                            required
                        />
                        @error('birth_time')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <flux:input
                            name="birth_place"
                            :label="__('出生地（市区町村まで）')"
                            type="text"
                            wire:model="birth_place"
                            placeholder="例）東京都渋谷区"
                            required
                        />
                        @error('birth_place')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <flux:button type="submit" variant="primary" class="w-full" style="background-color: #2A2E47; color: #FFFDF9;">
                    保存して次へ進む
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts.auth>

