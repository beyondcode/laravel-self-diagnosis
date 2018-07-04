<?php

namespace BeyondCode\SelfDiagnosis;

use Illuminate\Support\ServiceProvider;

class SelfDiagnosisServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

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
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'env-check');

        $this->app->bind('command.selfdiagnosis', SelfDiagnosisCommand::class);

        $this->commands([
            'command.selfdiagnosis',
        ]);
    }
}
