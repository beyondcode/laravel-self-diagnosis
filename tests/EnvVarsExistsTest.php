<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use BeyondCode\SelfDiagnosis\Checks\EnvVariablesExists;
use Orchestra\Testbench\TestCase;

class EnvVarsExistsTest extends TestCase
{
    /** @test */
    public function it_checks_if_env_vars_eixsts()
    {
        env('FILLED');
        env('DEPENDS', 'on_default');
        env('EMPTY');

        $config = [
            'directories' => [
                __DIR__
            ],
        ];

        $check = new EnvVariablesExists();
        $result = $check->check($config);

        $this->assertTrue($check->empty === 1);
        $this->assertFalse($result);
    }
}
