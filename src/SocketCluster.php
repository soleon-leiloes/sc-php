<?php
namespace SocketCluster;

use WebSocket\Client;
use Closure;
use Exception;

class SocketCluster
{
    /**
     * @var WebSocket\Client
     */
    protected $websocket;

    /**
     * @var string
     */
    protected $error;

    /**
     * Construct
     *
     * @param WebSocket\Client $websocket
     */
    public function __construct(Client $websocket)
    {
        $this->websocket = $websocket;
    }

    /**
     * Get Websocket
     *
     * @return WebSocket\Client
     */
    public function getWebsocket()
    {
        return $this->websocket;
    }

    /**
     * Publish Channel
     *
     * @param string       $channel
     * @param mixed        $data
     * @param Closure|null $callback
     *
     * @return void
     */
    public function publish($channel, $data, Closure $callback = null)
    {
        $pubData = [
            'channel' => $channel,
            'data' => $data,
        ];
        
        return $this->emit('#publish', $pubData, $callback);
    }

    /**
     * Emit Event
     *
     * @param string       $event
     * @param array        $data
     * @param Closure|null $callback
     *
     * @return void
     */
    public function emit($event, array $data, Closure $callback = null)
    {
        try {
            $eventData = [
                'event' => $event,
                'data' => $data,
            ];

            $sendData = (string) @json_encode($eventData);

            $this->websocket->send($sendData);

            $this->error = null;

            return true;

        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * Receive
     *
     * @return string
     */
    public function receive()
    {
        return $this->websocket->receive();
    }

    /**
     * Get Error
     *
     * @return string|null
     */
    public function error()
    {
        return $this->error;
    }
}
