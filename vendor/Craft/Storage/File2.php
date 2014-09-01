<?php

namespace Craft\Storage;

use Craft\Error\FileNotFound;

class File2 extends \ArrayObject
{

    /** @var resource */
    protected $handler;


    /**
     * Init file object
     * @param string $filename
     * @param string $mode
     * @throws FileNotFound
     */
    public function __construct($filename, $mode = 'a+')
    {
        // error
        if(!file_exists($filename)) {
            throw new FileNotFound('File ' . $filename . ' does not exist.');
        }

        // open file
        $this->handler = fopen($filename, $mode);

        // parse content
        $lines = explode("\n", $this->content());
        parent::__construct($lines);
    }


    /**
     * Get full content
     * @return string
     */
    public function content()
    {
        return stream_get_contents($this->handler);
    }


    /**
     * Close file
     */
    public function __destruct()
    {
        fclose($this->handler);
    }

} 