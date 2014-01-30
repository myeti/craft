<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Service\Logger;

use Craft\Box\Text\String;

class Log
{

    /** @var string */
    public $time;

    /** @var string */
    public $level;

    /** @var string */
    public $content;

    /**
     * Create new log
     * @param $level
     * @param $content
     */
    public function __construct($level, $content)
    {
        $this->time = date('Y-m-d H:i:s');
        $this->level = $level;
        $this->content = $content;
    }

    /**
     * Format log message
     * @return string
     */
    public function __toString()
    {
        return String::compose('[:system] :level - :content', [
            'system'      => $this->time,
            'level'     => $this->level,
            'content'   => $this->content,
        ]);
    }

} 