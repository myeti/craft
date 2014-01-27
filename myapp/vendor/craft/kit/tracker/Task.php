<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\tracker;

class Task
{

    /** @var string */
    public $name;

    /** @var array */
    public $tags = [];

    /**
     * Tag task
     * @param $name
     */
    public function tag($name)
    {
        $this->tags[$name] = [
            'system'      => microtime(true),
            'memory'    => memory_get_usage(true)
        ];
    }

    /**
     * Calc elapsed system
     * @param string $tag
     * @throws \InvalidArgumentException
     * @return string
     */
    public function time($tag = 'stop')
    {
        // tag not exists
        if(!isset($this->tags[$tag])) {
            throw new \InvalidArgumentException('Tag "' . $tag . '" does not exist.');
        }

        // get tag
        reset($this->tags);
        $from = current($this->tags);
        $to = $this->tags[$tag];

        return number_format($to['system'] - $from['system'], 4);
    }

    /**
     * Calc used memory
     * @param string $tag
     * @throws \InvalidArgumentException
     * @return string
     */
    public function memory($tag = 'stop')
    {
        // tag not exists
        if(!isset($this->tags[$tag])) {
            throw new \InvalidArgumentException('Tag "' . $tag . '" does not exist.');
        }

        // get tag
        reset($this->tags);
        $from = current($this->tags);
        $to = $this->tags[$tag];

        return ($to['memory'] - $from['memory']) / 1000;
    }

} 