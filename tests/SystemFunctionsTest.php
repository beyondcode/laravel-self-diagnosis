<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use BeyondCode\SelfDiagnosis\SystemFunctions;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class SystemFunctionsTest extends TestCase
{
    /**
     * @test
     */
    public function it_find_windows_as_os()
    {
        $this->markTestIncomplete('Using the const is very difficult to test');
        require __DIR__ . '/fixtures/windowsconst.php';

        $systemFunctions = new SystemFunctions();
        $this->assertTrue($systemFunctions->isWindowsOperatingSystem());
    }

    /**
     * @test
     */
    public function it_find_nowindows_as_os()
    {
        $this->markTestIncomplete('Using the const is very difficult to test');
        require __DIR__ . '/fixtures/linuxconst.php';

        $systemFunctions = new SystemFunctions();
        $this->assertFalse($systemFunctions->isWindowsOperatingSystem());
    }
}
