<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use org\bovigo\vfs\vfsStream;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\RoutesAreNotCached;

/**
 * @group checks
 * @group cache
 * @group routes
 */
class RoutesAreNotCachedTest extends TestCase
{

    /**
     * @test
     */
    public function it_will_detect_the_cached_config()
    {
        $originalBasePath = app()->basePath();

        // We get the relative path for the cache file from the basepath to set it and be flexible for changes
        $originalBasePath = app()->basePath();
        $originalCachePath = app()->getCachedRoutesPath();
        $relativeCache = substr($originalCachePath, strlen($originalBasePath));

        $root = vfsStream::setup();
        $cacheFile = vfsStream::newFile(ltrim($relativeCache,'/'))->setContent('dummy route cache content')->at($root);

        app()->setBasePath($root->url());
        $check = new RoutesAreNotCached();

        $this->assertFalse($check->check([]), 'The cache file isn\'t created but we get that it is created');
    }

    /**
     * @test
     */
    public function it_will_detect_the_not_cached_config()
    {
        $root = vfsStream::setup();
        app()->setBasePath($root->url());

        $check = new RoutesAreNotCached();

        $this->assertTrue($check->check([]), 'The cache file is created but we get that it isn\'t created');
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = new RoutesAreNotCached();
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = new RoutesAreNotCached();
        $this->assertInternalType('string', $check->message([]));
    }
}