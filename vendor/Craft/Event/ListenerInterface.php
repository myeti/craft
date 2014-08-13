<?php

namespace Craft\Event;

interface ListenerInterface
{

    /**
     * Subscribe to subject's events
     * @param EventInterface $subject
     */
    public function listen(EventInterface $subject);

} 