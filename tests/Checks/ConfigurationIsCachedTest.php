<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use org\bovigo\vfs\vfsStream;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\ConfigurationIsCached;

/**
 * @group checks
 * @group cache
 * @group config
 */
class ConfigurationIsCachedTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_detect_the_cached_config()
    {
        // We get the relative path for the cache file from the basepath to set it and be flexible for changes
        $originalBasePath = app()->basePath();
        $originalCachePath = app()->getCachedConfigPath();
        $relativeCache = substr($originalCachePath, strlen($originalBasePath));

        $root = vfsStream::setup();
        $cacheFile = vfsStream::newFile(ltrim($relativeCache,'/'))->setContent('dummy route cache content')->at($root);

        app()->setBasePath($root->url());

        $check = new ConfigurationIsCached();

        $this->assertTrue($check->check([]), 'The cache file is created but we get that it isn\'t created');
    }

    /**
     * @test
     */
    public function it_will_detect_the_not_cached_config()
    {
        $root = vfsStream::setup();
        app()->setBasePath($root->url());

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