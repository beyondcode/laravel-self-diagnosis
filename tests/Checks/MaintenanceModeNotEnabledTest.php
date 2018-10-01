<?php

namespace BeyondCode\SelfDiagnosis\Tests;

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
        return app()->storagePath().'/framework/down';
    }

    /**
     * be sure to remove the down file
     *
     * {@inheritDoc}
     * @see \Orchestra\Testbench\TestCase::tearDown()
     */
    public function tearDown()
    {
        @unlink($this->getDownFilePath());
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_will_detect_the_enabled_maintenance_mode()
    {
        touch($this->getDownFilePath());
        $check = new MaintenanceModeNotEnabled();

        $this->assertFalse($check->check([]), 'The maintenance mode is enabled but the check doesn\'t detect it');
    }

    /**
     * @test
     */
    public function it_will_detect_the_disabled_maintenance_mode()
    {
        @unlink($this->getDownFilePath());
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
