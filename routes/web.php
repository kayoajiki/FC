<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Volt::route('/', 'home')->name('home');

// 静的ページ（SEO対策）
Volt::route('four-pillars', 'pages.four-pillars')->name('four-pillars');
Volt::route('ziwei', 'pages.ziwei')->name('ziwei');
Volt::route('numerology', 'pages.numerology')->name('numerology');
Volt::route('tarot', 'pages.tarot')->name('tarot');
Volt::route('sonoka', 'pages.sonoka')->name('sonoka');
Volt::route('consultation', 'pages.consultation')->name('consultation');
Volt::route('products', 'pages.products')->name('products');

// SEO
Route::get('sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('robots.txt', [App\Http\Controllers\RobotsController::class, 'index'])->name('robots');

Volt::route('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
