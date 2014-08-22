<?php

namespace Craft\Trace\Logger;

use Craft\Error\DirectoryNotFound;
use Psr\Log\AbstractLogger;

class FileWriter extends AbstractLogger
{

    /** @var resource */
    protected $file;


    /**
     * Setup directory
     * @param string $directory
     * @throws DirectoryNotFound
     */
    public function __construct($directory)
    {
        // error
        $directory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if(!is_dir($directory)) {
            throw new DirectoryNotFound('Directory "' . $directory . '" not found.');
        }

        // current date
        $date = date('Y-m-d');

        // open file
        $this->file = fopen($directory . $date . '.logs', 'a+');

        // new session
        fwrite($this->file, date('Y-m-d H:i:s') . ' Hello :)' . "\n");
    }


    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        $string = date('Y-m-d H:i:s') . ' [' . $level . '] ' . $message;
        fwrite($this->file, $string . "\n");
    }


    /**
     * Close current log file
     */
    public function __destruct()
    {
        if($this->file) {
            fwrite($this->file, "\n");
            fclose($this->file);
        }
    }

} 