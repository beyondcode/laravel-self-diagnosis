<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Orchestra\Testbench\TestCase;
use Illuminate\Filesystem\Filesystem;
use BeyondCode\SelfDiagnosis\Checks\EnvFileExists;

class EnvFileExistsTest extends TestCase
{
    /** @test */
    public function it_checks_if_env_file_eixsts()
    {
        $filesystem = \Mockery::mock(Filesystem::class);

        $filesystem->shouldReceive('exists')
            ->with(base_path('.env'))
            ->andReturn(false);

        $check = new EnvFileExists($filesystem);

        $this->assertFalse($check->check([]));


        $filesystem = \Mockery::mock(Filesystem::class);

        $filesystem->shouldReceive('exists')
            ->with(base_path('.env'))
            ->andReturn(true);

        $check = new EnvFileExists($filesystem);

        $this->assertTrue($check->check([]));
    }
}
