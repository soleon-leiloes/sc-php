<?php

namespace SocketCluster\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Broadcasting\BroadcastManager;
use SocketCluster\WebSocket;
use SocketCluster\SocketCluster;
use SocketCluster\Laravel\SCBroadcaster;
use SocketCluster\Laravel\SCFacade;

class LaravelServiceProvider extends BaseServiceProvider
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
            $config = $app['config']['broadcasting']['connections']['socketcluster']['options'];
            $websocket = WebSocket::factory($config);
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
