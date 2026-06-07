<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MembershipController;

use App\Http\Controllers\ContactController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::view('/about', 'about')->name('about');
Route::view('/vision', 'vision')->name('vision');
Route::get('lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('lang.switch');

Route::get('/membership', [MembershipController::class , 'create'])->name('membership');
Route::post('/membership', [MembershipController::class , 'store'])->name('membership.store')->middleware('throttle:3,1');
Route::redirect('/training', '/events')->name('training');
Route::get('/news', [App\Http\Controllers\PostController::class , 'index'])->name('news');
Route::get('/news/{slug}', [App\Http\Controllers\PostController::class , 'show'])->name('news.show');
Route::get('/contact', [ContactController::class , 'index'])->name('contact');
Route::post('/contact', [ContactController::class , 'store'])->name('contact.store')->middleware('throttle:3,1');

Route::get('/be-a-trainer', [App\Http\Controllers\TrainerController::class , 'create'])->name('trainer.create');
Route::post('/be-a-trainer', [App\Http\Controllers\TrainerController::class , 'store'])->name('trainer.store')->middleware('throttle:3,1');

Route::get('/paths', [App\Http\Controllers\PathController::class , 'index'])->name('paths.index');
Route::get('/paths/{slug}', [App\Http\Controllers\PathController::class , 'show'])->name('paths.show');

Route::get('/events', [App\Http\Controllers\EventController::class , 'index'])->name('events.index');
Route::get('/events/{slug}', [App\Http\Controllers\EventController::class , 'show'])->name('events.show');
Route::post('/events/{slug}/register', [App\Http\Controllers\EventController::class , 'register'])
    ->name('events.register')
    ->middleware('auth:member');

// Public Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/verify-email', [App\Http\Controllers\Auth\VerificationController::class , 'show'])->name('verification.notice');
    Route::post('/verify-email', [App\Http\Controllers\Auth\VerificationController::class , 'verify'])->name('verification.verify');
    Route::post('/verify-email/resend', [App\Http\Controllers\Auth\VerificationController::class , 'resend'])->name('verification.resend');

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
    Route::get('/forgot-password/verify', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'showOtpVerificationForm'])->name('password.verify.otp');
    Route::post('/forgot-password/verify', [App\Http\Controllers\Auth\ForgotPasswordController::class , 'verifyOtp'])->name('password.verify.otp.post');
});

// Authenticated Community Member Routes
Route::middleware(['auth:member'])->group(function () {
    Route::post('/logout', [App\Http\Controllers\Auth\PublicLoginController::class , 'logout'])->name('public.logout');
    Route::get('/my-profile', [App\Http\Controllers\ProfileController::class , 'show'])->name('profile.show');
    Route::get('/my-profile/edit', [App\Http\Controllers\ProfileController::class , 'edit'])->name('profile.edit');
    Route::put('/my-profile', [App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');

    // Secure Account Deletion
    Route::post('/my-profile/delete/request', [App\Http\Controllers\AccountDeletionController::class , 'requestOtp'])->name('profile.delete.request');
    Route::get('/my-profile/delete/confirm', [App\Http\Controllers\AccountDeletionController::class , 'showConfirm'])->name('profile.delete.confirm');
    Route::post('/my-profile/delete/confirm', [App\Http\Controllers\AccountDeletionController::class , 'confirm'])->name('profile.delete.confirm.post');
    Route::post('/my-profile/email-preferences', [App\Http\Controllers\ProfileController::class, 'updateEmailPreference'])->name('member.email-preferences');

    // Certificate download (owner only)
    Route::get('/my-profile/certificates/{certificate}/download', [App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');
});

// Public certificate verification (target of the certificate QR code)
Route::get('/verify/{serial}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');

// Public email tracking (no auth required)
Route::get('/track/open/{token}', [App\Http\Controllers\TrackingController::class, 'openPixel'])->name('track.open')->middleware('throttle:60,1');
Route::get('/unsubscribe/{token}', [App\Http\Controllers\TrackingController::class, 'unsubscribePage'])->name('unsubscribe');
Route::post('/unsubscribe/{token}', [App\Http\Controllers\TrackingController::class, 'unsubscribeConfirm'])->name('unsubscribe.confirm')->middleware('throttle:5,1');
Route::post('/resubscribe/{token}', [App\Http\Controllers\TrackingController::class, 'resubscribeByToken'])->name('resubscribe.confirm')->middleware('throttle:5,1');

if (app()->environment('local')) {
    Route::get('/testemail', [\App\Http\Controllers\TestEmailController::class , 'index'])->name('testemail.index');
    Route::get('/testemail/{id}', [\App\Http\Controllers\TestEmailController::class , 'show'])->name('testemail.show');
    Route::post('/testemail/clear', [\App\Http\Controllers\TestEmailController::class , 'clear'])->name('testemail.clear');

    // Design-system component gallery (see DESIGN.md). Local only.
    Route::view('/ui-preview', 'ui-preview')->name('ui.preview');
}

// Fallback Route for true 404 handling with active Sessions (Arabic Localization support)
Route::fallback(App\Http\Controllers\FallbackController::class);
