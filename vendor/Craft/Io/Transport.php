<?php

namespace Craft\Io;

interface Transport
{

    /**
     * Open resource
     * @param mixed $resource
     * @return bool
     */
    public function open($resource);

    /**
     * Send data to service
     * @param mixed $data
     * @return mixed
     */
    public function send($data);

    /**
     * Receive data from service
     * @return mixed
     */
    public function receive();

    /**
     * Close resource
     * @return bool
     */
    public function close();

} 