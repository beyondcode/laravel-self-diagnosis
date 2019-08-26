<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\CachePrefixIsSet;
use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;

class CachePrefixIsSetTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [
            SelfDiagnosisServiceProvider::class,
        ];
    }

    /** @test */
    public function it_checks_if_the_cache_prefix_env_variable_is_set_in_the_env_file()
    {
        $this->app->setBasePath(__DIR__ . '/fixtures');

        $check = new CachePrefixIsSet();

        $this->assertFalse($check->check([]));
        $this->assertSame('A missing cache prefix could cause problems in shared hosting environments due to shared cache key access. Set a custom "CACHE_PREFIX" in your .env file.', $check->message([]));
    }
}
