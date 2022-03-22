<?php
namespace SocketCluster;

use WebSocket\Client;
use Closure;
use Exception;

class SocketCluster
{
    /**
     * @var \WebSocket\Client
     */
    protected $websocket;

    /**
     * @var string
     */
    protected $error;

    /**
     * Construct
     *
     * @param \WebSocket\Client $websocket
     */
    public function __construct(Client $websocket)
    {
        $this->websocket = $websocket;
    }
    
    /**
     * Handshake
     *
     * @param string|null  $token
     * @param Closure|null $callback
     *
     * @return boolean
     */
    public function handshake($token = null, Closure $callback = null)
    {
        $pubData = [
            'authToken' => $token,
        ];
        
        return $this->emit('#handshake', $pubData, $callback);
    }

    /**
     * Publish Channel
     *
     * @param string       $channel
     * @param mixed        $data
     * @param Closure|null $callback
     *
     * @return boolean
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
     * @param string $event
     * @param array  $data
     *
     * @return boolean
     */
    protected function emit($event, array $data)
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
     * Get Error
     *
     * @return string
     */
    public function error()
    {
        return $this->error;
    }
}
