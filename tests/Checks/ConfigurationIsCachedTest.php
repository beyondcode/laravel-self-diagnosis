<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\ConfigurationIsCached;

/**
 * @group checks
 */
class ConfigurationIsCachedTest extends TestCase
{
    /**
     * be sure toe remove the cached config
     *
     * {@inheritDoc}
     * @see \Orchestra\Testbench\TestCase::tearDown()
     */
    public function tearDown()
    {
        @unlink(app()->getCachedConfigPath());
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_will_detect_the_cached_config()
    {
        touch(app()->getCachedConfigPath());
        $check = new ConfigurationIsCached();

        $this->assertTrue($check->check([]), 'The cache file is created but we get that it isn\'t created');
    }

    /**
     * @test
     */
    public function it_will_detect_the_not_cached_config()
    {
        @unlink(app()->getCachedConfigPath());
        $check = new ConfigurationIsCached();

        $this->assertFalse($check->check([]), 'The cache file isn\'t created but we get that it is created');
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = new ConfigurationIsCached();
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = new ConfigurationIsCached();
        $this->assertInternalType('string', $check->message([]));
    }
}
