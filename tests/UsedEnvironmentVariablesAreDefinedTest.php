<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use BeyondCode\SelfDiagnosis\Checks\UsedEnvironmentVariablesAreDefined;
use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;
use Orchestra\Testbench\TestCase;

class UsedEnvironmentVariablesAreDefinedTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [
            SelfDiagnosisServiceProvider::class,
        ];
    }

    /** @test
     * @throws \Exception
     */
    public function it_checks_if_used_env_vars_are_defined()
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

        $check = new UsedEnvironmentVariablesAreDefined();

        $this->assertFalse($check->check($config));
        $this->assertTrue($check->amount === 1);
        $this->assertTrue(in_array('UNDEFINED', $check->undefined));
        $this->assertSame("1 used environmental variables are undefined: \nUNDEFINED", $check->message($config));
    }
}
