<?php

namespace Craft\Debug\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class DailyFile extends AbstractLogger
{

    /** @var Log[] */
    protected $logs = [];


    /**
     * Logs with an arbitrary level
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        $this->logs[] = new Log($level, $message);
    }


    /**
     * Get all logs
     */
    public function logs()
    {
        return $this->logs;
    }

} 