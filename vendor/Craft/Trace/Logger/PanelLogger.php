<?php

namespace Craft\Trace\Logger;

use Craft\Trace;
use Psr\Log\AbstractLogger;

class PanelLogger extends AbstractLogger
{

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
        $message = '<span class="log-' . $level . '">' . $message . '</span>';
        Trace\Panel::in('Logs')->add($message, $level);
    }


    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = [])
    {
        Trace\Panel::in('Debug')->add($message);
    }

} 