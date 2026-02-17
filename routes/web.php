<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipController;

use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/about', 'about')->name('about');
Route::view('/vision', 'vision')->name('vision');
Route::get('lang/{locale}', function ($locale) {
    \Illuminate\Support\Facades\Log::info('Route: Switching language to ' . $locale);
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
        \Illuminate\Support\Facades\Log::info('Route: Session set. Current session locale: ' . session('locale'));
    }
    return back();
})->name('lang.switch');

Route::get('/membership', [MembershipController::class , 'create'])->name('membership');
Route::post('/membership', [MembershipController::class , 'store'])->name('membership.store');
Route::view('/training', 'training')->name('training');
Route::view('/news', 'news')->name('news');
Route::get('/contact', [ContactController::class , 'index'])->name('contact');
Route::post('/contact', [ContactController::class , 'store'])->name('contact.store');

// Admin Authentication Routes
Route::get('/admincpanel/login', [App\Http\Controllers\AuthController::class , 'showLogin'])->name('login');
Route::post('/admincpanel/login', [App\Http\Controllers\AuthController::class , 'login'])->name('login.post');
Route::post('/admincpanel/logout', [App\Http\Controllers\AuthController::class , 'logout'])->name('logout');

// Protected Admin Routes
// Protected Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admincpanel/dashboard', [App\Http\Controllers\AdminController::class , 'index'])->name('admin.dashboard');

    // Members
    Route::get('/admincpanel/members', [App\Http\Controllers\AdminController::class , 'members'])->name('admin.members.index');
    Route::get('/admincpanel/members/export', [App\Http\Controllers\AdminController::class , 'exportMembers'])->name('admin.members.export');
    Route::get('/admincpanel/members/{member}', [App\Http\Controllers\AdminController::class , 'showMember'])->name('admin.members.show');
    Route::get('/admincpanel/members/{member}/edit', [App\Http\Controllers\AdminController::class , 'editMember'])->name('admin.members.edit');
    Route::put('/admincpanel/members/{member}', [App\Http\Controllers\AdminController::class , 'updateMember'])->name('admin.members.update');
    Route::delete('/admincpanel/members/{member}', [App\Http\Controllers\AdminController::class , 'destroyMember'])->name('admin.members.destroy');

    // Messages
    Route::get('/admincpanel/messages', [App\Http\Controllers\AdminController::class , 'messages'])->name('admin.messages.index');
    Route::get('/admincpanel/messages/export', [App\Http\Controllers\AdminController::class , 'exportMessages'])->name('admin.messages.export');
    Route::get('/admincpanel/messages/{contact}', [App\Http\Controllers\AdminController::class , 'showMessage'])->name('admin.messages.show');
    Route::delete('/admincpanel/messages/{contact}', [App\Http\Controllers\AdminController::class , 'destroyMessage'])->name('admin.messages.destroy');

    // Settings
    Route::get('/admincpanel/settings', [App\Http\Controllers\AdminController::class , 'settings'])->name('admin.settings');
    Route::post('/admincpanel/settings', [App\Http\Controllers\AdminController::class , 'updateSettings'])->name('admin.settings.update');
});
