<?php

namespace Craft\Io;

interface Resource
{

    /**
     * Open resource
     * @param mixed $resource
     * @return bool
     */
    public function open($resource);

    /**
     * Read data from resource
     * @return string
     */
    public function read();

    /**
     * Write data on resource
     * @param string $content
     * @return bool
     */
    public function write($content);

    /**
     * Close resource
     * @return bool
     */
    public function close();

} 