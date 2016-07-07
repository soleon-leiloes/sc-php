# SocketCluster - PHP library for interacting with the SocketCluster.io

[![Build Status](https://travis-ci.org/soleon-leiloes/sc-php.svg?branch=master)](https://travis-ci.org/soleon-leiloes/sc-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/soleon-leiloes/sc-php/?branch=master)
[![License](https://img.shields.io/packagist/l/soleon/sc-php.svg?style=flat-square)](https://packagist.org/packages/soleon/sc-php)


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