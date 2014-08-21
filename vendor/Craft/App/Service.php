<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

use Craft\Event\EventInterface;
use Craft\Event\ListenerInterface;

abstract class Service implements ListenerInterface
{

    /**
     * Get listening methods
     * @return array
     */
    abstract public function register();

    /**
     * Subscribe to subject's events
     * @param EventInterface $subject
     */
    public function listen(EventInterface $subject)
    {
        // get listening methods
        $events = (array)$this->register($subject);

        // bind to subject
        foreach($events as $event => $method) {
            $subject->on($event, [$this, $method]);
        }
    }

}