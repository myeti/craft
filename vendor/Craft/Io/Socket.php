<?php

namespace Craft\Io;

class Socket implements Transport
{

    /** @var resource */
    protected $socket;

    /**
     * Create socket
     * @param resource|int $domain
     * @param int $type
     * @param int $protocol
     */
    public function __construct($domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP)
    {
        if(is_resource($domain)) {
            $this->socket = $domain;
        }
        else {
            $this->socket = socket_create($domain, $type, $protocol);
        }
    }


    /**
     * Open IO resource
     * @param string $host
     * @param int $port
     * @return bool
     */
    public function open($host, $port = 80)
    {
        if($this->socket) {
            $this->close();
        }

        $this->socket = socket_connect($this->socket, $host, $port);
        return (bool)$this->socket;
    }


    /**
     * Read data from resource
     * @param int $length
     * @return string
     */
    public function receive($length = null)
    {
        return $length
            ? stream_socket_recvfrom($this->socket, (int)$length)
            : stream_get_contents($this->socket);
    }


    /**
     * Write data on resource
     * @param string $content
     * @param null $length
     * @return bool
     */
    public function send($content, $length = null)
    {
        if($length == null) {
            $length = strlen($content);
        }

        return stream_socket_sendto($this->socket, $content, (int)$length);
    }


    /**
     * Close IO resource
     * @return bool
     */
    public function close()
    {
        socket_close($this->socket);
        return true;
    }


    /**
     * Close file
     */
    public function __destruct()
    {
        $this->close();
    }


    /**
     * Direct connect
     * @param string $host
     * @param int $port
     * @return Socket
     */
    public static function on($host, $port = 80)
    {
        $socket = new self;
        $socket->open($host, $port);

        return $socket;
    }

}