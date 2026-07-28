<?php

namespace App\Providers;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginResponse::class, fn (): LoginResponse => new class implements LoginResponse
        {
            public function toResponse($request)
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
        Carbon::setLocale(config('idiomas.default', config('app.locale', 'es')));

        View::composer('layouts.app', function ($view): void {
            $routeName = request()->route()?->getName();
            $localizedSeo = is_string($routeName) ? trans("site.seo.routes.{$routeName}") : null;
            $seo = is_array($localizedSeo)
                ? $localizedSeo
                : config("seo.routes.{$routeName}", config('seo.default'));
            $url = url()->current();

            $view->with('seo', [
                'title' => __('site.seo.default_title'),
                'description' => __('site.seo.default_description'),
                ...$seo,
                'canonical' => $url,
                'image' => asset('favicon.png'),
            ]);
        });
    }
}
