<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
    }

    public function boot()
    {
        \DB::listen(function ($query) {
            $sql = $query->sql;
            \Log::info($sql, $query->bindings);
        });
    }
}
