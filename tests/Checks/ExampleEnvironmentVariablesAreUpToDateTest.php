<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;
use BeyondCode\SelfDiagnosis\Checks\ExampleEnvironmentVariablesAreUpToDate;

/**
 * @group checks
 */
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
        $this->app->setBasePath(__DIR__ . '/../fixtures');

        $check = new ExampleEnvironmentVariablesAreUpToDate();

        $this->assertFalse($check->check([]));
        $this->assertSame('These environment variables are defined in your .env file, but are missing in your .env.example:'.PHP_EOL.'KEY_FOUR', $check->message([]));
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(ExampleEnvironmentVariablesAreUpToDate::class);
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = app(ExampleEnvironmentVariablesAreUpToDate::class);
        $this->assertInternalType('string', $check->message([]));
    }
}
