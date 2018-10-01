<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\DatabaseCanBeAccessed;

/**
 * @group checks
 */
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

        $this->assertTrue($check->check([]), 'The check should success but has failed');
    }

    /** @test */
    public function it_checks_db_access_for_configured_connections()
    {
        $check = app(DatabaseCanBeAccessed::class);
        $this->assertFalse($check->check([]));

        $mock = \Mockery::mock(\Illuminate\Database\Connection::class);
        $mock->shouldReceive('getPdo');

        // the first call is the default
        DB::shouldReceive('connection')
            ->once()
            ->withNoArgs()
            ->andReturn($mock);

        // the second call is the connection that is configured
        DB::shouldReceive('connection')
            ->with('test')
            ->once()
            ->andReturn($mock);

        $this->assertTrue($check->check(['connections' => ['test']]), 'The check should success but has failed');
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = app(DatabaseCanBeAccessed::class);
        $this->assertInternalType('string', $check->name([]));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = app(DatabaseCanBeAccessed::class);
        $this->assertInternalType('string', $check->message([]));
    }
}
