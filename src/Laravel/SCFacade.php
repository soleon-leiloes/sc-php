<?php
namespace SocketCluster\Laravel;

use Illuminate\Support\Facades\Facade;

class SCFacade extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'SocketCluster';
    }
}
