<?php

namespace SocketCluster\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use SocketCluster\WebSocket;
use SocketCluster\SocketCluster;

class PimpleServiceProvider implements ServiceProviderInterface
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register(Container $app)
    {
        $app['socketcluster'] = function ($app) {
            $websocket = WebSocket::factory($app['socketcluster.options']);
            return new SocketCluster($websocket);
        };
    }
}
