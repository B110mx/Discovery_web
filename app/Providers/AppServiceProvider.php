<?php

namespace App\Providers;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ServiceProvider;
use Livewire\Features\SupportRedirects\Redirector;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginResponse::class, fn (): LoginResponse => new class implements LoginResponse
        {
            public function toResponse($request): RedirectResponse | Redirector
            {
                return redirect()->to(Filament::getUrl());
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('es');
    }
}
