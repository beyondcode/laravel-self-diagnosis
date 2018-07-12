<?php

namespace BeyondCode\SelfDiagnosis;

use Illuminate\Support\ServiceProvider;

class SelfDiagnosisServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap package services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->loadTranslationsFrom(__DIR__.'/../translations', 'self-diagnosis');

            $this->publishes([
                __DIR__.'/../translations' => resource_path('lang/vendor/self-diagnosis'),
            ], 'translations');

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('self-diagnosis.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'self-diagnosis');

        $this->app->bind('command.selfdiagnosis', SelfDiagnosisCommand::class);

        $this->commands([
            'command.selfdiagnosis',
        ]);
    }
}
