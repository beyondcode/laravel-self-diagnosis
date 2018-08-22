<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;
use BeyondCode\SelfDiagnosis\SystemFunctions;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\SupervisorProgramsAreRunning;
use PHPUnit\Framework\MockObject\MockObject;

class SupervisorProgramsAreRunningTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [
            SelfDiagnosisServiceProvider::class,
        ];
    }

    /** @test */
    public function it_succeeds_when_no_programs_need_to_run()
    {
        /** @var MockObject|SystemFunctions $systemFunctionsMock */
        $systemFunctionsMock = $this->createMock(SystemFunctions::class);

        $check = new SupervisorProgramsAreRunning($systemFunctionsMock);
        $result = $check->check([]);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_succeeds_when_all_programs_are_running()
    {
        $config = ['programs' => ['process-1', 'process-2']];
        $supervisorStatus = "process-1                          RUNNING   pid 3004, uptime 0:01:23\nprocess-2                          RUNNING   pid 3005, uptime 0:01:23";

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
            ->with('supervisorctl status')
            ->willReturn($supervisorStatus);

        $check = new SupervisorProgramsAreRunning($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_succeeds_when_all_programs_are_running_and_were_recently_restarted()
    {
        $config = [
            'programs' => ['process-1', 'process-2'],
            'restarted_within' => 300,
        ];
        $supervisorStatus = "process-1                          RUNNING   pid 3004, uptime 0:01:23\nprocess-2                          RUNNING   pid 3005, uptime 0:05:00";

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
            ->with('supervisorctl status')
            ->willReturn($supervisorStatus);

        $check = new SupervisorProgramsAreRunning($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_fails_when_shell_exec_is_not_available()
    {
        $config = ['programs' => ['process-1', 'process-2']];

        /** @var MockObject|SystemFunctions $systemFunctionsMock */
        $systemFunctionsMock = $this->createMock(SystemFunctions::class);
        $systemFunctionsMock->expects($this->once())
            ->method('isFunctionAvailable')
            ->with('shell_exec')
            ->willReturn(false);

        $check = new SupervisorProgramsAreRunning($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertFalse($result);
        $this->assertSame('The function "shell_exec" is not defined or disabled, so we cannot check the running programs.', $check->message($config));
    }

    /** @test */
    public function it_fails_when_run_on_windows()
    {
        $config = ['programs' => ['process-1', 'process-2']];

        /** @var MockObject|SystemFunctions $systemFunctionsMock */
        $systemFunctionsMock = $this->createMock(SystemFunctions::class);
        $systemFunctionsMock->expects($this->once())
            ->method('isFunctionAvailable')
            ->with('shell_exec')
            ->willReturn(true);
        $systemFunctionsMock->expects($this->once())
            ->method('isWindowsOperatingSystem')
            ->willReturn(true);

        $check = new SupervisorProgramsAreRunning($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertFalse($result);
        $this->assertSame('This check cannot be run on Windows.', $check->message($config));
    }

    /** @test */
    public function it_fails_when_supervisorctl_command_is_not_available()
    {
        $config = ['programs' => ['process-1', 'process-2']];

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
            ->with('supervisorctl status')
            ->willReturn('');

        $check = new SupervisorProgramsAreRunning($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertFalse($result);
        $this->assertSame('The "supervisorctl" command is not available on the current OS.', $check->message($config));
    }

    /** @test */
    public function it_fails_when_not_all_programs_are_running()
    {
        $config = ['programs' => ['process-1', 'process-2', 'process-3']];
        $supervisorStatus = "process-1                          RUNNING   pid 3004, uptime 0:01:23\nprocess-2                      STOPPED   Jul 12 03:37 PM";

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
            ->with('supervisorctl status')
            ->willReturn($supervisorStatus);

        $check = new SupervisorProgramsAreRunning($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertFalse($result);
        $this->assertSame("The following programs are not running or require a restart:\nprocess-2\nprocess-3", $check->message($config));
    }

    /** @test */
    public function it_fails_when_some_program_was_not_restarted_recently()
    {
        $config = [
            'programs' => ['process-1'],
            'restarted_within' => 300,
        ];
        $supervisorStatus = "process-1                          RUNNING   pid 3004, uptime 0:05:01";

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
            ->with('supervisorctl status')
            ->willReturn($supervisorStatus);

        $check = new SupervisorProgramsAreRunning($systemFunctionsMock);
        $result = $check->check($config);

        $this->assertFalse($result);
        $this->assertSame("The following programs are not running or require a restart:\nprocess-1", $check->message($config));
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(SupervisorProgramsAreRunning::class);
        $this->assertInternalType('string', $check->name([]));
    }
}
