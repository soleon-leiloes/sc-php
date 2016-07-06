<?php

namespace Tests\Laravel;

use Tests\TestCase;
use Mockery as m;
use SocketCluster\Laravel\SCServiceProvider;

class SCServiceProviderTest extends TestCase
{
    public function testShouldRegister()
    {
        $test = $this;

        // Mockery Application
        $app = m::mock('ArrayObject[]');
        $app->shouldReceive('alias')->once()->with('SocketCluster', 'SocketCluster\SocketCluster');
        $app->shouldReceive('singleton')
            ->once()
            ->andReturnUsing(
                // Make sure that the commands are being registered
                // with a closure that returns the correct
                // object.
                function ($name, $closure) use ($test, $app) {
                    $config = [
                        'secure' => true,
                        'host' => 'localhost',
                        'port' => 3000,
                        'path' => '/socketcluster/',
                    ];
                    $app['config'] = ['broadcasting' => ['connections' => ['socketcluster' => $config]]];
                    $shouldBe = ['SocketCluster' => 'SocketCluster\SocketCluster'];

                    $sc = $closure($app);
                    $test->assertInstanceOf($shouldBe[$name], $sc);
                    $uriExpected = 'wss://localhost:3000/socketcluster/';
                    $this->assertAttributeEquals($uriExpected, 'socket_uri', $sc->getWebsocket());
                }
            );

        
        $sp = new SCServiceProvider($app);
        $this->assertEquals(['SocketCluster'], $sp->provides());

        $sp->register();
    }

    public function testShouldBoot()
    {
        // Mockery Application
        $app = m::mock('ArrayObject[]');
        $app['SocketCluster'] = m::mock('SocketCluster\SocketCluster');
        $app->shouldReceive('singleton')->once();
        $app->shouldReceive('alias')->once();
        $app->shouldReceive('make')->once()->with('Illuminate\Broadcasting\BroadcastManager')->andReturn($app);
        $app->shouldReceive('extend')->once()->andReturnUsing(function ($driver, $callback) use ($app) {
            $this->assertEquals('socketcluster', $driver);

            $broadcaster = $callback($app);
            $this->assertInstanceOf('SocketCluster\Laravel\SCBroadcaster', $broadcaster);
            $this->assertAttributeEquals($app['SocketCluster'], 'socketcluster', $broadcaster);
        });

        $sp = new SCServiceProvider($app);

        $sp->boot();

        $sp->register();
    }
}
