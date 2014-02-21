<?php

namespace Craft\Box\Provider;

use Craft\Data\Provider;

interface SessionProvider extends Provider
{


    /**
     * Get session id
     * @return string
     */
    public function id();


    /**
     * Destroy session
     * @return bool
     */
    public function clear();

}