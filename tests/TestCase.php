<?php

namespace Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();
        
        \Mockery::close();
    }
}
