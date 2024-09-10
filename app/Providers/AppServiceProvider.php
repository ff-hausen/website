<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
        //            return (new MailMessage)
        //                ->subject('E-Mail Adresse bestätigen')
        //                ->line('Klicke auf die Schaltfläche unten, um Deine E-Mail-Adresse zu bestätigen.')
        //                ->action('E-Mail Adresse bestätigen', $url);
        //        });

    }
}
