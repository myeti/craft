<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Bundle;

use Craft\App\Bundle;
use Craft\App\Service;
use Craft\Routing\ApiRouter;

/**
 * Ready to use app
 */
class Api extends Bundle
{

    /**
     * Ready-to-use API
     * @param array $classes
     */
    public function __construct(array $classes)
    {
        $router = new ApiRouter($classes);
        $this->plug(new Service\Routing($router));

        $this->plug(new Service\Resolver);
        $this->plug(new Service\Firewall);

        $this->plug(new Service\JsonOutput);
    }

}