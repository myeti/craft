<?php

namespace Craft\Io\Socket;

use Craft\Io\Transport;
use Craft\Io\Socket;

class Server implements Transport
{

    /** @var resource */
    protected $socket;

    /** @var Socket[] */
    protected $clients = [];

    /** @var bool */
    protected $running = false;


    /**
     * Create socket
     * @param $domain
     * @param $type
     * @param $protocol
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
     * @return bool
     */
    public function open($host)
    {
        if($this->socket) {
            $this->close();
        }

        socket_bind($this->socket, $host);
        $listening = socket_listen($this->socket);

        // connect clients
        $this->running = true;
        while($this->running) {
            if($resource = socket_accept($this->socket)) {
                $client = new Socket($resource);
                $this->clients[] = $client;
                $this->run($client);
            }
        }

        return $listening;
    }


    /**
     * User process
     * @param Socket $client
     */
    public function run(Socket $client) {}


    /**
     * Read data from resource
     * @param int $length
     * @return string
     */
    public function receive($length = null)
    {
        return false;
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

        $written = true;
        foreach($this->clients as $client) {
            $written = $client->send($content, $length);
        }

        return $written;
    }


    /**
     * Stop running
     */
    public function stop()
    {
        $this->running = false;
    }


    /**
     * Close IO resource
     * @return bool
     */
    public function close()
    {
        $this->stop();
        socket_close($this->socket);
        foreach($this->clients as $client) {
            $client->close();
        }
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
     * Direct listen
     * @param string $host
     * @return Server
     */
    public static function listen($host)
    {
        $server = new self;
        $server->open($host);
        return $server;
    }

}