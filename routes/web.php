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

// コラム・記事
Volt::route('column', 'column.index')->name('column.index');
Volt::route('column/category/{category}', 'column.index')->name('column.category');
Volt::route('column/{slug}', 'column.show')->name('column.show');

// SEO
Route::get('sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('robots.txt', [App\Http\Controllers\RobotsController::class, 'index'])->name('robots');

Volt::route('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'birth.profile'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Volt::route('birth-profile', 'auth.birth-profile')->name('birth-profile');
});

Route::middleware(['auth', 'birth.profile'])->group(function () {
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

    // 管理画面（モック）
    Volt::route('admin/articles', 'admin.articles.index')->name('admin.articles.index');
    Volt::route('admin/articles/create', 'admin.articles.create')->name('admin.articles.create');
    Volt::route('admin/articles/{id}/edit', 'admin.articles.edit')->name('admin.articles.edit');
});
