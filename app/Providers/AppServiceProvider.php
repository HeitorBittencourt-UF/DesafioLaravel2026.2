<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Symfony\Component\Mailer\Event\MessageEvent;

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
        // Ignora a verificação SSL/cacert.pem no envio de e-mails em ambiente local
        if ($this->app->environment('local')) {
            Event::listen(MessageEvent::class, function (MessageEvent $event) {
                // Remove a exigência de CA do contexto da conexão
                stream_context_set_default([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ],
                ]);
            });
        }
    }
}