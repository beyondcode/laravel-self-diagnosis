<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use org\bovigo\vfs\vfsStream;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\MaintenanceModeNotEnabled;

/**
 * @group checks
 */
class MaintenanceModeNotEnabledTest extends TestCase
{
    /**
     * Get the path for the down file
     *
     * @return string
     */
    protected function getDownFilePath()
    {
        // this is from the isDownForMaintenance function from the \Illuminate\Foundation\Application class
        return app()->storagePath().'/framework/down';
    }

    /**
     * @test
     */
    public function it_will_detect_the_enabled_maintenance_mode()
    {
        // We get the relative path for the cache file from the basepath to set it and be flexible for changes
        $originalBasePath = app()->basePath();
        $originalCachePath = $this->getDownFilePath();
        $relativeCache = substr($originalCachePath, strlen($originalBasePath));

        $root = vfsStream::setup();
        $cacheFile = vfsStream::newFile(ltrim($relativeCache,'/'))->setContent('dummy route cache content')->at($root);

        app()->setBasePath($root->url());

        $check = new MaintenanceModeNotEnabled();

        $this->assertFalse($check->check([]), 'The maintenance mode is enabled but the check doesn\'t detect it');
    }

    /**
     * @test
     */
    public function it_will_detect_the_disabled_maintenance_mode()
    {
        $root = vfsStream::setup();
        app()->setBasePath($root->url());

        $check = new MaintenanceModeNotEnabled();

        $this->assertTrue($check->check([]), 'The maintenance mode is disabled but the check doesn\'t detect it');
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = new MaintenanceModeNotEnabled();
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = new MaintenanceModeNotEnabled();
        $this->assertInternalType('string', $check->message([]));
    }
}
