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

        return new Client($socket_uri, (isset($options['origin'])) ? array('headers' => array('origin' => trim($options['origin'], '/'))) : array());
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
        $default = [
            'scheme' => '',
            'host' => '',
            'port' => '',
            'path' => '',
            'query' => [],
            'fragment' => '',
        ];

        $optArr = (!is_array($options)) ? parse_url($options) : $options;
        $optArr = array_merge($default, $optArr);

        if (isset($optArr['secure'])) {
            $scheme = ((bool) $optArr['secure']) ? 'wss' : 'ws';

        } else {
            $scheme = (in_array($optArr['scheme'], ['wss', 'https'])) ? 'wss' : 'ws';
        }

        $query = $optArr['query'];
        if (!is_array($query)) {
            parse_str($optArr['query'], $query);
        }

        $host = trim($optArr['host'], "/");
        $port = !empty($optArr['port']) ? ":".$optArr['port'] : '';
        $path = trim($optArr['path'], "/");
        $path = !empty($path) ? $path."/" : '';
        $query = count($query) ? '?'.http_build_query($query) : '';

        return sprintf("%s://%s%s/%s%s", $scheme, $host, $port, $path, $query);
    }
}
