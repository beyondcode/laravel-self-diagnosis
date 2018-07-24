<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;
use BeyondCode\SelfDiagnosis\Checks\ExampleEnvironmentVariablesAreUpToDate;

class ExampleEnvironmentVariablesAreUpToDateTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [
            SelfDiagnosisServiceProvider::class,
        ];
    }

    /** @test */
    public function it_checks_if_example_env_variables_are_set_in_env_file()
    {
        $this->app->setBasePath(__DIR__ . '/fixtures');

        $check = new ExampleEnvironmentVariablesAreUpToDate();

        $this->assertFalse($check->check([]));
        $this->assertSame('These environment variables are defined in your .env file, but are missing in your .env.example:'.PHP_EOL.'KEY_FOUR', $check->message([]));
    }
}
