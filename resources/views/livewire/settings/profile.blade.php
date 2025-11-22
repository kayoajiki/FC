<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public ?string $birth_date = null;
    public ?string $birth_time = null;
    public ?string $birth_hour = null;
    public ?string $birth_minute = null;
    public ?string $birth_place = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->birth_date = $user->birth_date?->format('Y-m-d');
        $this->birth_time = $user->birth_time;
        $this->birth_place = $user->birth_place;
        
        // 既存のbirth_timeからhourとminuteを分解
        if ($this->birth_time && $this->birth_time !== '不明') {
            $parts = explode(':', $this->birth_time);
            if (count($parts) === 2) {
                $this->birth_hour = $parts[0];
                $this->birth_minute = $parts[1];
            }
        }
    }

    /**
     * Update birth_time when hour or minute changes
     */
    public function updateBirthTime(): void
    {
        if ($this->birth_hour && $this->birth_minute) {
            $this->birth_time = sprintf('%02d:%02d', $this->birth_hour, $this->birth_minute);
        } elseif ($this->birth_time !== '不明') {
            $this->birth_time = null;
        }
    }

    /**
     * Update when birth_time radio button changes
     */
    public function updatedBirthTime(): void
    {
        if ($this->birth_time === '不明') {
            $this->birth_hour = null;
            $this->birth_minute = null;
        }
    }

    /**
     * Get Japanese prefectures list.
     */
    public function getPrefectures(): array
    {
        return [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
            '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
            '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
            '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県',
        ];
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'birth_date' => [
                'nullable',
                'date',
                'before_or_equal:today',
                'after_or_equal:1900-01-01',
            ],
            'birth_time' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '不明' && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $value)) {
                        $fail('出生時刻は「不明」または「HH:MM」形式で入力してください。');
                    }
                },
            ],
            'birth_place' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value !== null) {
                        $prefectures = $this->getPrefectures();
                        if (!in_array($value, $prefectures)) {
                            $fail('出生地は都道府県を選択してください。');
                        }
                    }
                },
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your profile information')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="space-y-4 border-t pt-6">
                <h3 class="text-lg font-medium">{{ __('Birth Information') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('This information is used for fortune calculations.') }}</p>

                <flux:input wire:model="birth_date" :label="__('Birth Date')" type="date" autocomplete="bday" />

                <div>
                    <flux:field>
                        <flux:label>{{ __('Birth Time') }}</flux:label>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm mb-2 text-gray-600 dark:text-gray-400">{{ __('Unknown') }}</label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" wire:model="birth_time" value="不明" class="w-4 h-4 text-accent border-gray-300 focus:ring-accent">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Birth time is unknown') }}</span>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm mb-2 text-gray-600 dark:text-gray-400">{{ __('Specify time') }}</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs mb-1 text-gray-600 dark:text-gray-400">{{ __('Hour') }}</label>
                                        <select wire:model="birth_hour" wire:change="updateBirthTime" data-flux-control class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent dark:border-gray-600 dark:bg-gray-800">
                                            <option value="">--</option>
                                            @for($hour = 0; $hour < 24; $hour++)
                                                <option value="{{ sprintf('%02d', $hour) }}">{{ sprintf('%02d', $hour) }}時</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs mb-1 text-gray-600 dark:text-gray-400">{{ __('Minute') }}</label>
                                        <select wire:model="birth_minute" wire:change="updateBirthTime" data-flux-control class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent dark:border-gray-600 dark:bg-gray-800">
                                            <option value="">--</option>
                                            @for($minute = 0; $minute < 60; $minute++)
                                                <option value="{{ sprintf('%02d', $minute) }}">{{ sprintf('%02d', $minute) }}分</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <flux:error name="birth_time" />
                        <flux:description>{{ __('Select your birth time or choose "Unknown" if you don\'t know. Ziwei Doushu requires birth time.') }}</flux:description>
                    </flux:field>
                </div>

                <div>
                    <flux:field>
                        <flux:label>{{ __('Birth Place (Prefecture)') }}</flux:label>
                        <select wire:model="birth_place" data-flux-control class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent dark:border-gray-600 dark:bg-gray-800">
                            <option value="">{{ __('Select...') }}</option>
                            @foreach($this->getPrefectures() as $prefecture)
                                <option value="{{ $prefecture }}">{{ $prefecture }}</option>
                            @endforeach
                        </select>
                        <flux:error name="birth_place" />
                        <flux:description>{{ __('Select the prefecture where you were born.') }}</flux:description>
                    </flux:field>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
