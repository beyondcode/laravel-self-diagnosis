<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Orchestra\Testbench\TestCase;
use BeyondCode\SelfDiagnosis\Checks\RedisCanBeAccessed;
use PHPUnit\Framework\MockObject\MockObject;

class RedisCanBeAccessedTest extends TestCase
{
    /** @test */
    public function it_checks_default_cache_access()
    {
        $check = app(RedisCanBeAccessed::class);
        $this->assertFalse($check->check([]));

        /** @var MockObject|Connection $connectionMock */
        $connectionMock = $this->getMockBuilder(Connection::class)
            ->setMethods(['isConnected', 'createSubscription']) // we have to declare the abstract method createSubscription
            ->getMock();
        $connectionMock->expects($this->once())
            ->method('isConnected')
            ->willReturn(true);

        Redis::shouldReceive('connection')
            ->withNoArgs()
            ->andReturn($connectionMock);
        $this->assertTrue($check->check([]));
    }

    /** @test */
    public function it_checks_named_cache_access()
    {
        $config = [
            'default_connection' => false,
            'connections' => [
                'some_connection',
            ],
        ];

        $check = app(RedisCanBeAccessed::class);
        $this->assertFalse($check->check($config));

        /** @var MockObject|Connection $connectionMock */
        $connectionMock = $this->getMockBuilder(Connection::class)
            ->setMethods(['isConnected', 'createSubscription']) // we have to declare the abstract method createSubscription
            ->getMock();
        $connectionMock->expects($this->once())
            ->method('isConnected')
            ->willReturn(true);

        Redis::shouldReceive('connection')
            ->with('some_connection')
            ->andReturn($connectionMock);
        $this->assertTrue($check->check($config));
    }
}
