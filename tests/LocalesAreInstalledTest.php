<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;
use BeyondCode\SelfDiagnosis\SystemFunctions;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\LocalesAreInstalled;
use PHPUnit\Framework\MockObject\MockObject;

class LocalesAreInstalledTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [
            SelfDiagnosisServiceProvider::class,
        ];
    }

    /** @test */
    public function it_succeeds_when_no_locales_are_required()
    {
        /** @var MockObject|SystemFunctions $systemFunctionsMock */
        $systemFunctionsMock = $this->createMock(SystemFunctions::class);

        $check = new LocalesAreInstalled($systemFunctionsMock);
        $result = $check->check([]);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_succeeds_when_all_locales_are_installed()
    {
        $config = ['required_locales' => ['en_US', 'en_US.utf8', 'de_DE', 'de_DE.utf8', 'de_AT', 'de_AT.utf8']];

        /** @var MockObject|SystemFunctions $systemFunctionsMock */
        $systemFunctionsMock = $this->createMock(SystemFunctions::class);
        $systemFunctionsMock->expects($this->once())
            ->method('isFunctionAvailable')
            ->with('shell_exec')
            ->willReturn(true);
        $systemFunctionsMock->expects($this->once())
            ->method('isWindowsOperatingSystem')
            ->willReturn(false);
        $systemFunctionsMock->expects($this->once())
            ->method('callShellExec')
            ->with('locale -a')
            ->willReturn("de_AT\nde_AT.utf8\nde_DE\nde_DE.utf8\nen_US\nen_US.utf8");

        $check = new LocalesAreInstalled($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_fails_when_shell_exec_is_not_available()
    {
        $config = ['required_locales' => ['en_US', 'en_US.utf8']];

        /** @var MockObject|SystemFunctions $systemFunctionsMock */
        $systemFunctionsMock = $this->createMock(SystemFunctions::class);
        $systemFunctionsMock->expects($this->once())
            ->method('isFunctionAvailable')
            ->with('shell_exec')
            ->willReturn(false);

        $check = new LocalesAreInstalled($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertFalse($result);
        $this->assertSame('The function "shell_exec" is not defined or disabled, so we cannot check the locales.', $check->message($config));
    }

    /** @test */
    public function it_fails_when_run_on_windows()
    {
        $config = ['required_locales' => ['en_US', 'en_US.utf8']];

        /** @var MockObject|SystemFunctions $systemFunctionsMock */
        $systemFunctionsMock = $this->createMock(SystemFunctions::class);
        $systemFunctionsMock->expects($this->once())
            ->method('isFunctionAvailable')
            ->with('shell_exec')
            ->willReturn(true);
        $systemFunctionsMock->expects($this->once())
            ->method('isWindowsOperatingSystem')
            ->willReturn(true);

        $check = new LocalesAreInstalled($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertFalse($result);
        $this->assertSame('This check cannot be run on Windows.', $check->message($config));
    }

    /** @test */
    public function it_fails_when_locale_command_not_available()
    {
        $config = ['required_locales' => ['en_US', 'en_US.utf8']];

        /** @var MockObject|SystemFunctions $systemFunctionsMock */
        $systemFunctionsMock = $this->createMock(SystemFunctions::class);
        $systemFunctionsMock->expects($this->once())
            ->method('isFunctionAvailable')
            ->with('shell_exec')
            ->willReturn(true);
        $systemFunctionsMock->expects($this->once())
            ->method('isWindowsOperatingSystem')
            ->willReturn(false);
        $systemFunctionsMock->expects($this->once())
            ->method('callShellExec')
            ->with('locale -a')
            ->willReturn('');

        $check = new LocalesAreInstalled($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertFalse($result);
        $this->assertSame('The "locale -a" command is not available on the current OS.', $check->message($config));
    }

    /** @test */
    public function it_fails_when_locales_are_missing()
    {
        $config = ['required_locales' => ['en_US', 'en_US.utf8', 'de_DE', 'de_DE.utf8', 'de_AT', 'de_AT.utf8']];

        /** @var MockObject|SystemFunctions $systemFunctionsMock */
        $systemFunctionsMock = $this->createMock(SystemFunctions::class);
        $systemFunctionsMock->expects($this->once())
            ->method('isFunctionAvailable')
            ->with('shell_exec')
            ->willReturn(true);
        $systemFunctionsMock->expects($this->once())
            ->method('isWindowsOperatingSystem')
            ->willReturn(false);
        $systemFunctionsMock->expects($this->once())
            ->method('callShellExec')
            ->with('locale -a')
            ->willReturn("de_DE\nde_DE.utf8\nen_US");

        $check = new LocalesAreInstalled($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertFalse($result);
        $this->assertSame("The following locales are missing:\nen_US.utf8\nde_AT\nde_AT.utf8", $check->message($config));
    }
}
