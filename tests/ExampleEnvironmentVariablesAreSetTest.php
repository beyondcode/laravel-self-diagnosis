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

        $this->assertFalse($check->check());
        $this->assertSame('self-diagnosis::checks.example_environment_variables_are_set.message', $check->message());
    }
}