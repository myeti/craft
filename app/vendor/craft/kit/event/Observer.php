<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\event;

abstract class Observer
{

    /**
     * Get notification from event subject
     * @param Event $event
     * @return mixed
     */
    abstract public function notify(Event $event);

} 