<?php

namespace Craft\Log;

use Psr\Log\LoggerInterface;

interface WriterInterface extends LoggerInterface
{

    /**
     * Get all logs
     * @return string
     */
    public function logs();

}