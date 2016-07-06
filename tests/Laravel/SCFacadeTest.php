<?php
namespace Tests\Laravel;

use Tests\TestCase;
use SocketCluster\Laravel\SCFacade;

class SCFacadeTest extends TestCase
{
    public function testFacade()
    {
        $facade = new SCFacade;
        
        $class = new \ReflectionClass(get_class($facade));
        $method = $class->getMethod('getFacadeAccessor');
        $method->setAccessible(true);
        $return = $method->invokeArgs($facade, []);

        $this->assertEquals('SocketCluster', $return);
    }
}
