<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\DatabaseCanBeAccessed;

class DatabaseCanBeAccessedTest extends TestCase
{
    /** @test */
    public function it_checks_db_access()
    {
        $check = app(DatabaseCanBeAccessed::class);
        $this->assertFalse($check->check([]));

        $mock = \Mockery::mock(\Illuminate\Database\Connection::class);
        $mock->shouldReceive('getPdo');

        DB::shouldReceive('connection')->andReturn($mock);

        $this->assertTrue($check->check([]));
    }
}
