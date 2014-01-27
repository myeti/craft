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

class Tracker
{

    /** @var array */
    public $tasks = [];


    /**
     * Add tag
     * @param $task
     * @param $tag
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function tag($task, $tag)
    {
        // task does not exists
        if(!isset($this->tasks[$task])) {
            throw new \InvalidArgumentException('Task "' . $task . '" does not exist.');
        }

        // add tag
        $this->tasks[$task]->tag($tag);
    }


    /**
     * Start task
     * @param string $task
     * @return float
     */
    public function start($task)
    {
        // create task
        $this->tasks[$task] = new Task();
        $this->tasks[$task]->name = $task;

        // tag start
        $this->tag($task, 'start');

        return $this->get($task);
    }


    /**
     * Stop task
     * @param string $task
     * @return Task
     */
    public function stop($task)
    {
        // tag stop
        $this->tag($task, 'stop');

        return $this->get($task);
    }


    /**
     * Get current elapsed system of task
     * @param $task
     * @throws \InvalidArgumentException
     * @return Task
     */
    public function get($task)
    {
        // task does not exists
        if(!isset($this->tasks[$task])) {
            throw new \InvalidArgumentException('Task "' . $task . '" does not exist.');
        }

        return $this->tasks[$task];
    }


    /**
     * Start, stop and get
     * @param callable $callable
     * @return float
     */
    public static function track(\Closure $callable)
    {
        $self = new self();
        $self->start('*');
        $callable();
        $self->stop('*');
        return $self->get('*');
    }

} 