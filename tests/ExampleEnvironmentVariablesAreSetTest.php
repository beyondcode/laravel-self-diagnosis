<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;
use BeyondCode\SelfDiagnosis\Checks\ExampleEnvironmentVariablesAreSet;

class ExampleEnvironmentVariablesAreSetTest extends TestCase
{
    protected $originalEnvironment = [];

    public function getPackageProviders($app)
    {
        return [
            SelfDiagnosisServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['KEY_ONE', 'KEY_TWO', 'KEY_THREE', 'KEY_FOUR'] as $variable) {
            $this->originalEnvironment[$variable] = [
                'process' => \getenv($variable),
                'env' => $_ENV[$variable] ?? null,
                'server' => $_SERVER[$variable] ?? null,
            ];

            \putenv($variable);
            unset($_ENV[$variable], $_SERVER[$variable]);
        }
    }

    protected function tearDown(): void
    {
        foreach ($this->originalEnvironment as $variable => $values) {
            if ($values['process'] === false) {
                \putenv($variable);
            } else {
                \putenv($variable . '=' . $values['process']);
            }

            if ($values['env'] === null) {
                unset($_ENV[$variable]);
            } else {
                $_ENV[$variable] = $values['env'];
            }

            if ($values['server'] === null) {
                unset($_SERVER[$variable]);
            } else {
                $_SERVER[$variable] = $values['server'];
            }
        }

        parent::tearDown();
    }

    /** @test */
    public function it_checks_if_example_env_variables_are_set_in_env_file()
    {
        $this->app->setBasePath(__DIR__ . '/fixtures');

        $check = new ExampleEnvironmentVariablesAreSet();

        $this->assertFalse($check->check([]));
        $this->assertSame('These environment variables are missing in your .env file, but are defined in your .env.example:'.PHP_EOL.'KEY_TWO', $check->message([]));
    }

    /** @test */
    public function it_checks_if_all_example_env_variables_are_set_in_env_file()
    {
        $this->app->setBasePath(__DIR__ . '/fixtures-complete-env');

        $check = new ExampleEnvironmentVariablesAreSet();

        $this->assertTrue($check->check([]));
    }

    /** @test */
    public function it_checks_if_example_env_variables_are_set_in_process_environment_without_env_file()
    {
        $this->app->setBasePath(__DIR__ . '/fixtures-without-env');

        \putenv('KEY_ONE=value1');
        \putenv('KEY_TWO=value2');
        \putenv('KEY_THREE=value3');

        $check = new ExampleEnvironmentVariablesAreSet();

        $this->assertTrue($check->check([]));
    }

    /** @test */
    public function it_reports_example_env_variables_missing_from_env_file_and_process_environment()
    {
        $this->app->setBasePath(__DIR__ . '/fixtures-without-env');

        \putenv('KEY_ONE=value1');
        \putenv('KEY_TWO=value2');

        $check = new ExampleEnvironmentVariablesAreSet();

        $this->assertFalse($check->check([]));
        $this->assertSame('These environment variables are missing in your .env file, but are defined in your .env.example:'.PHP_EOL.'KEY_THREE', $check->message([]));
    }

    /** @test */
    public function it_treats_empty_process_environment_values_as_set()
    {
        $this->app->setBasePath(__DIR__ . '/fixtures-without-env');

        \putenv('KEY_ONE=value1');
        \putenv('KEY_TWO=');
        \putenv('KEY_THREE=value3');

        $check = new ExampleEnvironmentVariablesAreSet();

        $this->assertTrue($check->check([]));
    }

    /** @test */
    public function it_combines_env_file_and_process_environment_variables()
    {
        $basePath = sys_get_temp_dir() . '/self-diagnosis-combined-env-' . uniqid('', true);
        mkdir($basePath);

        file_put_contents($basePath . '/.env.example', implode(PHP_EOL, [
            'KEY_ONE=value1',
            'KEY_TWO=value2',
            'KEY_THREE=value3',
        ]));
        file_put_contents($basePath . '/.env', 'KEY_ONE=foo' . PHP_EOL);

        \putenv('KEY_TWO=value2');
        \putenv('KEY_THREE=value3');

        try {
            $this->app->setBasePath($basePath);

            $check = new ExampleEnvironmentVariablesAreSet();

            $this->assertTrue($check->check([]));
        } finally {
            unlink($basePath . '/.env.example');
            unlink($basePath . '/.env');
            rmdir($basePath);
        }
    }
}
