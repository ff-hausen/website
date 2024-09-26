<?php

namespace App\Providers;

use App\Models\Passport\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Passport\Passport;
use Laravel\Passport\Scope;

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
        $this->bootPassport();
    }

    protected function bootPassport(): void
    {
        Passport::hashClientSecrets();

        Passport::useClientModel(Client::class);

        Passport::authorizationView(function ($parameters) {
            /** @var Client $client */
            /** @var User $user */
            /** @var array<Scope> $scopes */
            /** @var Request $request */
            /** @var string $authToken */
            [
                'client' => $client,
                'user' => $user,
                'scopes' => $scopes,
                'request' => $request,
                'authToken' => $authToken
            ] = $parameters;

            return Inertia::render('Auth/PassportAuthorize', [
                'client' => $client,
                'scopes' => $scopes,
                'authToken' => $authToken,
                'state' => $request->state,
            ]);
        });
    }
}
