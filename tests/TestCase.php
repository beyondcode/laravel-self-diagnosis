<?php

namespace BeyondCode\SelfDiagnosis\Tests;


class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton(
            'Illuminate\Contracts\Console\Kernel',
            'BeyondCode\SelfDiagnosis\Tests\Console\Kernel'
        );
    }
}
