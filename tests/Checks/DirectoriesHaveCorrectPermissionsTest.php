<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\DirectoriesHaveCorrectPermissions;

class DirectoriesHaveCorrectPermissionsTest extends TestCase
{
    /** @test */
    public function it_checks_if_directories_are_writable()
    {
        $config = [
            'directories' => [
                storage_path(),
                base_path('bootstrap/cache'),
            ],
        ];

        $filesystem = \Mockery::mock(Filesystem::class);

        $filesystem->shouldReceive('isWritable')
            ->andReturn(false);

        $check = new DirectoriesHaveCorrectPermissions($filesystem);

        $this->assertFalse($check->check($config));


        $filesystem = \Mockery::mock(Filesystem::class);

        $filesystem->shouldReceive('isWritable')
            ->andReturn(true);

        $check = new DirectoriesHaveCorrectPermissions($filesystem);

        $this->assertTrue($check->check($config));
    }
}
