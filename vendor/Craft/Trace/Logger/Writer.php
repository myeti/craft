<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Trace\Logger;

use Psr\Log\AbstractLogger;

class Writer extends AbstractLogger
{

    /** @var array */
    protected $logs = [];


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
        $this->logs[] = date('Y-m-d H:i:s.') . '[' . $level . ']' . $message;
    }


    /**
     * Get all logs
     * @param string $glue
     * @return string
     */
    public function logs($glue = '<br/>')
    {
        return implode($glue, $this->logs);
    }

} 