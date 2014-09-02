<?php

namespace Craft\Trace\Logger;

use Craft\Error\DirectoryNotFound;
use Psr\Log\AbstractLogger;

class EmailLogger extends AbstractLogger
{

    /** @var string */
    protected $email;

    /** @var string */
    protected $content;


    /**
     * Setup email address
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email;
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
        $this->content .= date('Y-m-d H:i:s') . ' [' . $level . '] ' . $message . "\n";
    }


    /**
     * Send email
     */
    public function __destruct()
    {
        mail($this->email, 'application logs', $this->content);
    }

} 