<?php

namespace App\Providers;

use App\Mail\MessageInsti;
use App\Services\Courriel;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Le lien reçu par email prouve que l'adresse est bien celle de l'utilisateur.
        Event::listen(PasswordReset::class, function ($event) {
            $event->user->forceFill(['email_verified_at' => $event->user->email_verified_at ?: now()])->save();
            Courriel::envoyer($event->user->email, new MessageInsti('Votre mot de passe a changé', 'Mot de passe réinitialisé', [
                "Bonjour {$event->user->name},",
                'Votre mot de passe vient d’être réinitialisé.',
                'Si ce n’est pas vous, contactez immédiatement l’administration de l’INSTI.',
            ]));
        });
    }
}
