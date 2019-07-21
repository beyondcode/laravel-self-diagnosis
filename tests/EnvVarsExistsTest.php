<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use BeyondCode\SelfDiagnosis\Checks\EnvVariablesExists;
use Orchestra\Testbench\TestCase;

class EnvVarsExistsTest extends TestCase
{
    /** @test
     * @throws \Exception
     */
    public function it_checks_if_env_vars_exists()
    {
        env('FILLED');
        env('NOT_FILLED');
        env('FILLED_WITH_FALSE');

        env('DEPENDING_ON_DEFAULT', 'default');
        env('DEFAULT_IS_FALSE', false);

        env('UNDEFINED');
        // Doubles should be ignored
        env('UNDEFINED');

        $config = [
            'directories' => [
                __DIR__
            ],
        ];

        $check = new EnvVariablesExists();
        $result = $check->check($config);

        $this->assertTrue($check->undefined === 1);
        $this->assertFalse($result);
    }
}
