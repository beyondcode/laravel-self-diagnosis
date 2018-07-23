<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\SystemFunctions;

class SystemFunctionsTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @test
     */
    public function it_find_windows_as_os()
    {
        require __DIR__ . '/fixtures/windowsconst.php';

        $systemFunctions = new SystemFunctions();
        $this->assertTrue($systemFunctions->isWindowsOperatingSystem());
    }

    /**
     * @runInSeparateProcess
     * @test
     */
    public function it_find_nowindows_as_os()
    {
        require __DIR__ . '/fixtures/linuxconst.php';

        $systemFunctions = new SystemFunctions();
        $this->assertFalse($systemFunctions->isWindowsOperatingSystem());
    }
}