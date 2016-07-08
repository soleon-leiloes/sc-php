<?php

namespace Tests;

use Tests\TestCase;
use Mockery as m;
use SocketCluster\WebSocket;

class WebSocketTest extends TestCase
{
    public function testFactoryWithString()
    {
        $uri = 'https://localhost:3001/foobar/?user=1&pass=2';
        $websocket = WebSocket::factory($uri);

        $expected = new \WebSocket\Client('wss://localhost:3001/foobar/?user=1&pass=2');
        $this->assertEquals($expected, $websocket);
    }

    public function testFactoryWithArray()
    {
        $options = [
          'secure' => false,
          'host' => 'localhost',
          'port' => '4000',
          'path' => '/socketcluster/',
          'query' => [
            'servicekey' => 'abc'
          ],
        ];
        $websocket = WebSocket::factory($options);

        $expected = new \WebSocket\Client('ws://localhost:4000/socketcluster/?servicekey=abc');
        $this->assertEquals($expected, $websocket);
    }

    public function testFactoryWithOptionsDefault()
    {
        $options = [
          'host' => 'localhost',
        ];
        $websocket = WebSocket::factory($options);

        $expected = new \WebSocket\Client('ws://localhost/');
        $this->assertEquals($expected, $websocket);
    }
}
