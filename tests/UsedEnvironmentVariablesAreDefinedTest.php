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
        $envPath = base_path('.env');
        file_put_contents($envPath, implode("\n", [
            'FILLED=value',
            'NOT_FILLED=another',
            'FILLED_WITH_FALSE=false',
            'GET_FILLED=email@example.com',
            'DEPENDING_ON_DEFAULT=custom',
            'DEFAULT_IS_FALSE=false',
            'GET_DEPENDING_ON_DEFAULT=default',
        ]));

        \Dotenv\Dotenv::createImmutable(base_path())->load();

        env('FILLED');
        env('NOT_FILLED');
        env('FILLED_WITH_FALSE');
        getenv('GET_FILLED');

        env('DEPENDING_ON_DEFAULT', 'default');
        env('DEFAULT_IS_FALSE', false);
        getenv('GET_DEPENDING_ON_DEFAULT', 'default');

        env('UNDEFINED');
        getenv('GET_UNDEFINED');
        env('UNDEFINED'); 
        getenv('GET_UNDEFINED');

        $config = [
            'directories' => [
                __DIR__,
            ],
        ];

        $check = new \BeyondCode\SelfDiagnosis\Checks\UsedEnvironmentVariablesAreDefined();

        $this->assertFalse($check->check($config));
        $this->assertSame(2, $check->amount);
        $this->assertContains('UNDEFINED', $check->undefined);
        $this->assertContains('GET_UNDEFINED', $check->undefined);
        $this->assertStringContainsString('2 used environmental variables are undefined:', $check->message($config));
        $this->assertStringContainsString('UNDEFINED', $check->message($config));
        $this->assertStringContainsString('GET_UNDEFINED', $check->message($config));

        unlink($envPath);
    }
}
