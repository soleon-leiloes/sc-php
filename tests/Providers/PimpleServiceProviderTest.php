<?php

namespace Tests\Providers;

use Tests\TestCase;
use Mockery as m;
use SocketCluster\Providers\PimpleServiceProvider;
use SocketCluster\WebSocket;

class PimpleServiceProviderTest extends TestCase
{
    public function testShouldRegister()
    {
        $test = $this;

        // Mockery Application
        $app = m::mock('Pimple\Container[]');
        $options = [
            'secure' => true,
            'host' => 'localhost',
            'port' => '3000',
            'path' => '/socketcluster/',
        ];
        $app['socketcluster.options'] = $options;

        $sp = new PimpleServiceProvider;
        $sp->register($app);

        $this->assertInstanceOf('SocketCluster\SocketCluster', $app['socketcluster']);

        $expected = WebSocket::factory('wss://localhost:3000/socketcluster/');
        $this->assertAttributeEquals($expected, 'websocket', $app['socketcluster']);
    }
}
