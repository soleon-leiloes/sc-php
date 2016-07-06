<?php

namespace Tests\Laravel;

use Tests\TestCase;
use Mockery as m;
use SocketCluster\Laravel\SCBroadcaster;

class SCBroadcasterTest extends TestCase
{
    public function testConstructShouldInstanceOf()
    {
        $sc = m::mock('SocketCluster\SocketCluster');

        $broadcaster = new SCBroadcaster($sc);

        $this->assertInstanceOf('Illuminate\Contracts\Broadcasting\Broadcaster', $broadcaster);
        $this->assertAttributeEquals($sc, 'socketcluster', $broadcaster);
    }

    public function testBroadcastShouldPublishChannels()
    {
        $sc = m::mock('SocketCluster\SocketCluster');
        $sc->shouldReceive('publish')->once()->with('channe1', ['foo' => 'bar']);
        $sc->shouldReceive('publish')->once()->with('channe2', ['foo' => 'bar']);

        $broadcaster = new SCBroadcaster($sc);

        $channels = ['channe1', 'channe2'];

        $broadcaster->broadcast($channels, '', ['foo' => 'bar']);
    }
}
