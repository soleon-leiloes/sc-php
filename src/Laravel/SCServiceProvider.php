<?php

namespace SocketCluster\Laravel;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Broadcasting\BroadcastManager;
use WebSocket\Client as WebSocketClient;
use SocketCluster\SocketCluster;

class SCServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSocketCluster();
    }

    /**
     * Register SocketCluster
     *
     * @return void
     */
    protected function registerSocketCluster()
    {
        $this->app->singleton('SocketCluster', function ($app) {
            $config = $app['config']['broadcasting']['connections']['socketcluster'];

            if (empty($config['uri'])) {
                $scheme = $config['secure']==true ? 'wss' : 'ws';
                $host   = trim($config['host'], "/");
                $port   = !empty($config['port']) ? ":".$config['port'] : '';
                $path   = trim($config['path'], "/");
                $path   = !empty($path) ? $path . "/" : '';
                $config['uri'] = sprintf("%s://%s%s/%s", $scheme, $host, $port, $path);
            }
            
            $websocket  = new WebSocketClient($config['uri']);
            return new SocketCluster($websocket);
        });

        $this->app->alias('SocketCluster', SocketCluster::class);
    }

    /**
     * Register new BroadcastManager in boot
     *
     * @return void
     */
    public function boot()
    {
        $this->app
            ->make(BroadcastManager::class)
            ->extend('socketcluster', function ($app) {
                return new SCBroadcaster($app['SocketCluster']);
            });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['SocketCluster'];
    }
}
