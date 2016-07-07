<?php
namespace SocketCluster;

use WebSocket\Client;

class WebSocket
{
    /**
     * Get Client
     *
     * @return Client
     */
    public static function factory($options)
    {
        $socket_uri = self::parseOptions($options);

        return new Client($socket_uri);
    }

    /**
     * Parse Options
     *
     * @param string|array $options
     *
     * @return void
     */
    protected static function parseOptions($options)
    {
        if (!is_array($options)) {
            $options = parse_url($options);
        }

        $default = ['scheme'=>'', 'host'=>'', 'port'=>'', 'path'=>'', 'query'=>[], 'fragment'=>''];
        $options = array_merge($default, $options);

        if (isset($options['secure'])) {
            $scheme = ((bool) $options['secure']) ? 'wss' : 'ws';

        } else {
            $scheme = (in_array($options['scheme'], ['wss', 'https'])) ? 'wss' : 'ws';
        }

        $query = $options['query'];
        if (!is_array($query)) {
            parse_str($options['query'], $query);
        }

        $host = trim($options['host'], "/");
        $port = !empty($options['port']) ? ":".$options['port'] : '';
        $path = trim($options['path'], "/");
        $path = !empty($path) ? $path."/" : '';
        $query = count($query) ? '?'.http_build_query($query) : '';

        return sprintf("%s://%s%s/%s%s", $scheme, $host, $port, $path, $query);
    }
}
