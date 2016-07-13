# SocketCluster - PHP
[![Build Status](https://travis-ci.org/soleon-leiloes/sc-php.svg?branch=master)](https://travis-ci.org/soleon-leiloes/sc-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/soleon/sc-php/v/stable)](https://packagist.org/packages/soleon/sc-php)
[![License](https://img.shields.io/packagist/l/soleon/sc-php.svg?style=flat-square)](https://packagist.org/packages/soleon/sc-php)

## PHP library for interacting with the SocketCluster.io
It's an unofficial client php for [SocketCluster](http://socketcluster.io/) (Is an open source realtime WebSocket framework for Node.js from [socketcluster.io](http://www.socketcluster.io) for PHP 5.5.9+).

## Contents

- [Installation](#installation)
- [Usage Basic](#usage-basic)
- [Integrations](#integrations)
  - [Laravel](#laravel-framework)
  - [Pimple (eg:Silex, Slim)](#pimple)
- [Contribution](#contribution)
- [License](#license)

## Installation
You can install this [package](https://packagist.org/packages/soleon/sc-php) by simply run this composer command:

```
composer require soleon/sc-php
```

## Usage Basic
~~~php

$optionsOrUri = 'wss://localhost:443/socketcluster/?servicekey=abc'

OR

$optionsOrUri = [
  'secure' => true,
  'host' => 'localhost',
  'port' => '443',
  'path' => '/socketcluster/',
  'query' => [
    'servicekey' => 'abc'
  ],
];

$websocket = \SocketCluster\WebSocket::factory($optionsOrUri);
$socket = new \SocketCluster\SocketCluster($websocket);

// Event Emit
$data = ['message' => 'FooBar'];
$socket->publish('CHANNEL_NAME', $data);
~~~

## Integrations

### Laravel Framework

Then, add this service provider in your providers array `[app/config/app.php]`:

~~~php
SocketCluster\Providers\LaravelServiceProvider::class,
~~~

Then, add this Facade to your aliases array `[app/config/app.php]`:

~~~php
'SocketCluster' => SocketCluster\Laravel\SCFacade::class
~~~

Next you have to copy the configuration to your `connections` array `[app/config/broadcasting.php]`:

~~~php
/*
 * Set default broadcasting driver to socketcluster
 */
'default' => env('BROADCAST_DRIVER', 'socketcluster'),

'socketcluster' => [
    'driver' => 'socketcluster',
    'options' => [
      'secure' => true,
      'host' => 'localhost',
      'port' => '443',
      'path' => '/socketcluster/',
      'query' => [],
    ],
]
~~~

**Usage Laravel**

- With Facade
```php
SocketCluster::publish('ChannelName', ['message' => 'Test publish!!']);
```

With Event Listener

Add a custom broadcast event to your application example `[app/events/PublishToSocketClusterEvent.php]`:

```php
namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PublishToSocketClusterEvent implements ShouldBroadcast
{
    use SerializesModels

    /**
     * Content Message
     * @var string
     */
    public $message;

    /**
     * Construct Event
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return array
     */
    public function broadcastOn()
    {
        return ['channelName'];
    }

    /**
     * Get the data to send.
     * @return array
     */
    public function broadcastWith()
    {
      return [
        'message' => $this->message
      ]
    }
}
```

Now to publish in your application simply fire the event:

```php
event(new App\Events\PublishToSocketClusterEvent('Test publish!!'));
```

### Pimple 

[Pimple](http://pimple.sensiolabs.org/) is a simple PHP Dependency Injection Container

Examples of frameworks that use: [Silex](http://silex.sensiolabs.org/), [Slim](http://www.slimframework.com/)

Registering this service provider

~~~php
$app->register(new SocketCluster\Providers\PimpleServiceProvider(), array(
    'socketcluster.options' => array(
      'secure' => true,
      'host' => 'localhost',
      'port' => '443',
      'path' => '/socketcluster/',
      'query' => [],
    )
));
~~~

**Usage Pimple**

~~~php
$app['socketcluster']->publish('CHANNEL_NAME', $data);
~~~


## Contribution

Support follows PSR-2 and PSR-4 PHP coding standards, and semantic versioning.
Fork this project and make a pull request!

## License
This project is free software distributed under the terms of the [MIT License](http://opensource.org/licenses/mit-license.php).