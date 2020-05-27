<?php

namespace Geecko\Skills;

use Geecko\Skills\Console\Command\UpdateSkillServiceTasks;
use Geecko\Skills\Console\Command\UpdateSkillServiceTemplates;
use Geecko\Skills\Controllers\WebhookController;
use Illuminate\Support\ServiceProvider;

class SkillsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make(WebhookController::class);
        $this->mergeConfigFrom(
            __DIR__ . '/../config/skillservice.php', 'skillservice'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        include __DIR__ . '/routes.php';
        $this->publishes([
            __DIR__ . '/../config/skillservice.php' => config_path('skillservice.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        if ($this->app->runningInConsole()) {
            $this->commands([
                UpdateSkillServiceTasks::class,
                UpdateSkillServiceTemplates::class,
            ]);
        }

    }
}
