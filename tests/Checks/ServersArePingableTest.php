<?php

namespace BeyondCode\SelfDiagnosis\Tests\Checks;

use BeyondCode\SelfDiagnosis\Checks\ServersArePingable;
use BeyondCode\SelfDiagnosis\Exceptions\InvalidConfigurationException;
use BeyondCode\SelfDiagnosis\SelfDiagnosisServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * @group checks
 */
class ServersArePingableTest extends TestCase
{
    public function getPackageProviders($app)
    {
        return [
            SelfDiagnosisServiceProvider::class,
        ];
    }

    /** @test */
    public function it_succeeds_when_no_servers_are_given()
    {
        $check = new ServersArePingable();
        $this->assertTrue($check->check([]));
    }

    /** @test */
    public function it_fails_when_numeric_server_host_is_given()
    {
        $config = ['servers' => [1234567890]];

        $check = new ServersArePingable();

        $this->expectException(InvalidConfigurationException::class);
        $check->check($config);
    }

    /** @test */
    public function it_fails_when_no_host_key_is_given_for_server_in_array_form()
    {
        $config = ['servers' => [['port' => 80]]];

        $check = new ServersArePingable();

        $this->expectException(InvalidConfigurationException::class);
        $check->check($config);
    }

    /** @test */
    public function it_fails_when_not_supported_keys_are_given_for_server_in_array_form()
    {
        $config = ['servers' => [['host' => 'localhost', 'unsupported_param' => true]]];

        $check = new ServersArePingable();

        $this->expectException(InvalidConfigurationException::class);
        $check->check($config);
    }

    /** @test */
    public function it_fails_when_the_server_ip_cannot_be_resolved()
    {
        $config = ['servers' => [['host' => 'somethingthatdoesnotexist.local', 'timeout' => 1]]];

        $check = new ServersArePingable();
        $this->assertFalse($check->check($config));
        $this->assertSame("The server 'somethingthatdoesnotexist.local' (port: n/a) is not reachable (timeout after 1 seconds).", $check->message($config));
    }

    /** @test */
    public function it_fails_when_the_server_ip_cannot_be_reached()
    {
        $config = ['servers' => [['host' => '254.254.254.254', 'timeout' => 1]]];

        $check = new ServersArePingable();
        $this->assertFalse($check->check($config));
        $this->assertSame("The server '254.254.254.254' (port: n/a) is not reachable (timeout after 1 seconds).", $check->message($config));
    }

    /** @test */
    public function it_succeeds_when_the_server_ip_can_be_reached()
    {
        $config = ['servers' => [['host' => 'www.google.com', 'timeout' => 2]]];

        $check = new ServersArePingable();
        $this->assertTrue($check->check($config));
    }

    /**
     * @test
     */
    public function it_succeeds_when_the_server_ip_can_be_reached_and_we_only_use_the_hostname()
    {
        $config = ['servers' => ['www.google.com']];

        $check = new ServersArePingable();
        $this->assertTrue($check->check($config));
    }

    /**
     * @test
     */
    public function it_succeeds_when_we_add_the_port()
    {
        // we check the http and https host
        $config = ['servers' => [['host' => 'www.google.com', 'port' => 80], ['host' => 'www.google.com', 'port' => 443]]];

        $check = new ServersArePingable();
        $this->assertTrue($check->check($config));
    }

    /**
     * @test
     */
    public function it_returns_a_message_for_the_check()
    {
        $check = new ServersArePingable();
        $this->assertInternalType('string', $check->message([]));
    }

    /**
     * @test
     */
    public function it_returns_a_name_for_the_check()
    {
        $check = new ServersArePingable();
        $this->assertInternalType('string', $check->name([]));
    }
}
