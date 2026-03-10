<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $settings = \App\Models\Setting::all()->pluck('value', 'key');
            \Illuminate\Support\Facades\View::share('settings', $settings);
        }

        // Force Vite to generate URLs relative to the root domain 
        // because the server's document root IS public_html
        Vite::useBuildDirectory('build');

        if (app()->environment('local')) {
            Event::listen(MessageSent::class , function (MessageSent $event) {
                try {
                    $emails = Cache::get('test_emails_log', []);
                    $message = $event->message;

                    $html = $message->getHtmlBody();
                    $content = is_resource($html) ? stream_get_contents($html) : $html;

                    $toAddresses = [];
                    foreach ($message->getTo() as $address) {
                        $toAddresses[] = $address->getAddress();
                    }

                    $newEmail = [
                        'id' => uniqid(),
                        'date' => now()->format('Y-m-d H:i:s'),
                        'subject' => $message->getSubject(),
                        'to' => implode(', ', $toAddresses),
                        'content' => $content,
                    ];

                    array_unshift($emails, $newEmail);
                    $emails = array_slice($emails, 0, 50); // Keep last 50

                    Cache::put('test_emails_log', $emails, now()->addDays(2));
                }
                catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Local email capture failed: ' . $e->getMessage());
                }
            });
        }
    }
}
