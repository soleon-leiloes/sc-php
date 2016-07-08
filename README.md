# SocketCluster - PHP
[![Build Status](https://travis-ci.org/soleon-leiloes/sc-php.svg?branch=master)](https://travis-ci.org/soleon-leiloes/sc-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/?branch=master)
[![License](https://img.shields.io/packagist/l/soleon/sc-php.svg?style=flat-square)](https://packagist.org/packages/soleon/sc-php)

## PHP library for interacting with the SocketCluster.io
It's an unofficial client php for [SocketCluster](http://socketcluster.io/) (Is an open source realtime WebSocket framework for Node.js from [socketcluster.io](http://www.socketcluster.io) for PHP 5.4+).

## Contents

- [Installation](#installation)
- [Usage](#usage)
- [Laravel](#laravel)
- [Contribution](#contribution)
- [License](#license)

## Installation
You can install this [package](https://packagist.org/packages/soleon/sc-php) by simply run this composer command:

```
composer require soleon/sc-php
```

## Usage
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

### Laravel

Then, add this service provider in your providers array `[app/config/app.php]`:

~~~php
SocketCluster\Laravel\SCServiceProvider::class,
~~~

Then, add this Facade to your aliases array `[app/config/app.php]`:

~~~php
'SocketCluster' => SocketCluster\Laravel\SCFacade::class
~~~

Next you have to copy the configuration to your `connections` array `[app/config/broadcasting.php]`:

~~~php
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

Edit `[app/config/broadcasting.php]`:
~~~php
/*
 * Set default broadcasting driver to socketcluster
 */
'default' => env('BROADCAST_DRIVER', 'socketcluster'),
~~~


## Contribution

Support follows PSR-2 and PSR-4 PHP coding standards, and semantic versioning.
Fork this project and make a pull request!

## License
This project is free software distributed under the terms of the [MIT License](http://opensource.org/licenses/mit-license.php).