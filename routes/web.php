<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipController;

use App\Http\Controllers\ContactController;

use App\Models\Event;
use Carbon\Carbon;

Route::get('/', function () {
    $upcomingEvents = Event::where('is_active', true)
        ->where('end_date', '>=', Carbon::now()) // Not ended yet
        ->orderBy('start_date', 'asc')
        ->take(3)
        ->get();

    $latestNews = \App\Models\Post::where('is_published', true)
        ->latest()
        ->take(2)
        ->get();

    return view('welcome', compact('upcomingEvents', 'latestNews'));
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
Route::post('/membership', [MembershipController::class , 'store'])->name('membership.store')->middleware('throttle:3,1');
Route::get('/training', function () {
    return redirect()->route('events.index');
})->name('training');
Route::get('/news', [App\Http\Controllers\PostController::class , 'index'])->name('news');
Route::get('/news/{slug}', [App\Http\Controllers\PostController::class , 'show'])->name('news.show');
Route::get('/contact', [ContactController::class , 'index'])->name('contact');
Route::post('/contact', [ContactController::class , 'store'])->name('contact.store')->middleware('throttle:3,1');

Route::get('/events', [App\Http\Controllers\EventController::class , 'index'])->name('events.index');
Route::get('/events/{slug}', [App\Http\Controllers\EventController::class , 'show'])->name('events.show');

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

    // Membership Tiers
    Route::patch('/admincpanel/membership-tiers/{membershipTier}/toggle', [App\Http\Controllers\Admin\MembershipTierController::class , 'toggle'])->name('admin.membership-tiers.toggle');
    Route::resource('/admincpanel/membership-tiers', App\Http\Controllers\Admin\MembershipTierController::class)->names('admin.membership-tiers');

    // Settings
    Route::get('/admincpanel/settings', [App\Http\Controllers\AdminController::class , 'settings'])->name('admin.settings');
    Route::post('/admincpanel/settings', [App\Http\Controllers\AdminController::class , 'updateSettings'])->name('admin.settings.update');

    // Events Management
    Route::patch('/admincpanel/events/{event}/toggle', [App\Http\Controllers\Admin\EventController::class , 'toggle'])->name('admin.events.toggle');
    Route::resource('/admincpanel/events', App\Http\Controllers\Admin\EventController::class)->names('admin.events');

    // Posts & News Management
    Route::patch('/admincpanel/posts/{post}/toggle', [App\Http\Controllers\Admin\PostController::class , 'toggle'])->name('admin.posts.toggle');
    Route::resource('/admincpanel/posts', App\Http\Controllers\Admin\PostController::class)->names('admin.posts');

    // Admin User Management (Super Admin Only checks inside controller)
    Route::resource('/admincpanel/users', App\Http\Controllers\Admin\UserController::class)->names('admin.users');

    // Profile Management
    Route::get('/admincpanel/profile', [App\Http\Controllers\Admin\ProfileController::class , 'edit'])->name('admin.profile.edit');
    Route::put('/admincpanel/profile', [App\Http\Controllers\Admin\ProfileController::class , 'update'])->name('admin.profile.update');
});
