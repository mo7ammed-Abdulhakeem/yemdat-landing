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
Route::post('/events/{slug}/register', [App\Http\Controllers\EventController::class , 'register'])
    ->name('events.register')
    ->middleware('auth:member');

// Public Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\PublicLoginController::class , 'showLogin'])->name('public.login');
    Route::post('/login', [App\Http\Controllers\Auth\PublicLoginController::class , 'login'])->name('public.login.post');

    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class , 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class , 'register'])->name('register.post');

    Route::get('/claim-profile', [App\Http\Controllers\Auth\ClaimProfileController::class , 'showClaimForm'])->name('claim.profile');
    Route::post('/claim-profile', [App\Http\Controllers\Auth\ClaimProfileController::class , 'verifyEmail'])->name('claim.profile.verify');
    Route::get('/claim-profile/set-password/{token}', [App\Http\Controllers\Auth\ClaimProfileController::class , 'showSetPasswordForm'])->name('claim.profile.set-password');
    Route::post('/claim-profile/set-password', [App\Http\Controllers\Auth\ClaimProfileController::class , 'setPassword'])->name('claim.profile.set-password.post');

    Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'verifyIdentity'])->name('password.verify');
});

// Authenticated Community Member Routes
Route::middleware(['auth:member'])->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\PublicLoginController::class , 'logout'])->name('public.logout');
    Route::get('/my-profile', [App\Http\Controllers\ProfileController::class , 'show'])->name('profile.show');
    Route::get('/my-profile/edit', [App\Http\Controllers\ProfileController::class , 'edit'])->name('profile.edit');
    Route::put('/my-profile', [App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');
});

// Admin Authentication Routes
Route::get('/admincpanel/login', [App\Http\Controllers\AuthController::class , 'showLogin'])->name('login');
Route::post('/admincpanel/login', [App\Http\Controllers\AuthController::class , 'login'])->name('login.post');
Route::post('/admincpanel/logout', [App\Http\Controllers\AuthController::class , 'logout'])->name('logout');

// Protected Admin Routes
// Protected Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admincpanel/dashboard', [App\Http\Controllers\AdminController::class , 'index'])->name('admin.dashboard');

    // Analytics Dashboards
    Route::get('/admincpanel/analytics/members', [App\Http\Controllers\Admin\AnalyticsController::class , 'members'])->name('admin.analytics.members');
    Route::get('/admincpanel/analytics/events', [App\Http\Controllers\Admin\AnalyticsController::class , 'events'])->name('admin.analytics.events');
    Route::get('/admincpanel/analytics/api/member-data', [App\Http\Controllers\Admin\AnalyticsController::class , 'memberData'])->name('admin.analytics.api.members');
    Route::get('/admincpanel/analytics/api/event-data', [App\Http\Controllers\Admin\AnalyticsController::class , 'eventData'])->name('admin.analytics.api.events');

    // Members
    Route::get('/admincpanel/members', [App\Http\Controllers\AdminController::class , 'members'])->name('admin.members.index');
    Route::get('/admincpanel/members/export', [App\Http\Controllers\AdminController::class , 'exportMembers'])->name('admin.members.export');
    Route::get('/admincpanel/members/{member}/export-single', [App\Http\Controllers\AdminController::class , 'exportSingleMember'])->name('admin.members.export_single');
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
    Route::get('/admincpanel/events/export-all', [App\Http\Controllers\Admin\EventController::class , 'exportAll'])->name('admin.events.export_all');
    Route::get('/admincpanel/events/{event}/export', [App\Http\Controllers\Admin\EventController::class , 'exportMembers'])->name('admin.events.export');
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

// Fallback Route for true 404 handling with active Sessions (Arabic Localization support)
Route::fallback(function () {
    abort(404);
});
