<?php

namespace Dust\Providers;

use Illuminate\Support\ServiceProvider;
use Dust\Console\Commands\StoryMakeCommand;

class DustServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/nebula.php', 'nebula'
        );
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                StoryMakeCommand::class,
            ]);
        }
        $this->publishes([
            __DIR__.'/../../config/nebula.php' => config_path('nebula.php'),
        ]);
    }
}
