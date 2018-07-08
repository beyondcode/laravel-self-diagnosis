<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\ExampleEnvironmentVariablesAreSet;

class ExampleEnvironmentVariablesAreSetTest extends TestCase
{
    /** @test */
    public function it_checks_if_example_env_variables_are_set_in_env_file()
    {
        $this->app->setBasePath(__DIR__ . '/fixtures');

        $check = new ExampleEnvironmentVariablesAreSet();

        $this->assertFalse($check->check([]));
        $this->assertSame('These example environment variables are missing in your .env file, but are defined in your .env.example: '.PHP_EOL.'KEY_TWO', $check->message([]));
    }
}
