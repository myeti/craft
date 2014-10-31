<?php

namespace Craft\Debug\Logger;

use Craft\Box\Mog;
use Craft\Debug\Error\DirectoryNotFound;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class FileLogger extends AbstractLogger
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

        // open file
        $filename = Mog::sapi() . '-' . date('Y-m-d') . '.log';
        $this->file = fopen($directory . $filename, 'a+');

        // new session
        $this->log(LogLevel::INFO, 'New session');
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
        $string = '[' . date('Y-m-d H:i:s') . '] ';
        if($level != LogLevel::INFO) {
            $string .= strtoupper($level) . ' : ';
        }
        $string .= $message;
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