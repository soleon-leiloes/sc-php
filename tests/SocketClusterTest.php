<?php

namespace Tests;

use Tests\TestCase;
use Mockery as m;
use SocketCluster\SocketCluster;

class SocketClusterTest extends TestCase
{
    public function testEmitShouldSendSuccess()
    {
        $mockWS = m::mock('WebSocket\Client');
        $mockWS->shouldReceive('send')
            ->once()
            ->with('{"event":"ping","data":{"value1":"foo","value2":"bar"}}');

        $sc = new SocketCluster($mockWS);
        $this->assertEquals($mockWS, $sc->getWebsocket());
        
        $data = ['value1' => 'foo', 'value2' => 'bar'];
        $this->assertTrue($sc->emit('ping', $data));
    }

    public function testEmitShouldError()
    {
        $mockWS = m::mock('WebSocket\Client');
        $mockWS->shouldReceive('send')
            ->once()
            ->with('{"event":"ping","data":["value-emit"]}')
            ->andThrow(new \Exception('Send-Error'));

        $sc = new SocketCluster($mockWS);

        $this->assertFalse($sc->emit('ping', ['value-emit']));

        $this->assertEquals('Send-Error', $sc->error());
    }

    public function testPublishShouldSendChannel()
    {
        $mockWS = m::mock('WebSocket\Client');
        $mockWS->shouldReceive('send')
            ->once()
            ->with('{"event":"#publish","data":{"channel":"pong","data":{"value1":"foo","value2":"bar"}}}');

        $sc = new SocketCluster($mockWS);

        $data = ['value1' => 'foo', 'value2' => 'bar'];
        $this->assertTrue($sc->publish('pong', $data));
    }

    public function testReceiveShouldReturnWs()
    {
        $mockWS = m::mock('WebSocket\Client');
        $mockWS->shouldReceive('receive')
            ->once()
            ->withNoArgs()
            ->andReturn('receive-foobar');

        $sc = new SocketCluster($mockWS);
        $this->assertEquals('receive-foobar', $sc->receive());
    }
}
