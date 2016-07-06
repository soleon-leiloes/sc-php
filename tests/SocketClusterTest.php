<?php

namespace Tests;

use Tests\TestCase;
use Mockery as m;
use SocketCluster\SocketCluster;

class SocketClusterTest extends TestCase
{
    public function testPublishShouldSendError()
    {
        $mockWS = m::mock('WebSocket\Client');
        $mockWS->shouldReceive('send')
            ->once()
            ->with('{"event":"#publish","data":{"channel":"ping","data":{"foo":"bar"}}}')
            ->andThrow(new \Exception('Send-Error'));

        $sc = new SocketCluster($mockWS);

        $this->assertFalse($sc->publish('ping', ['foo' => 'bar']));

        $this->assertEquals('Send-Error', $sc->error());
    }

    public function testPublishShouldSendSuccess()
    {
        $mockWS = m::mock('WebSocket\Client');
        $mockWS->shouldReceive('send')
            ->once()
            ->with('{"event":"#publish","data":{"channel":"pong","data":{"value1":"foo","value2":"bar"}}}');

        $sc = new SocketCluster($mockWS);

        $data = ['value1' => 'foo', 'value2' => 'bar'];
        $this->assertTrue($sc->publish('pong', $data));
    }
}
