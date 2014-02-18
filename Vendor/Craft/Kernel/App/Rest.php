<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Kernel\App;

use Craft\Kernel\App;
use Craft\Reflect\Injector;
use Craft\View\Engine\Json;

class Rest extends App
{

    /**
     * Setup router
     * @param array $routes
     * @param Injector $injector
     * @param callable $wrapper
     */
    public function __construct(array $routes, Injector $injector = null, \Closure $wrapper = null)
    {
        // create json engine
        $engine = new Json($wrapper);

        // init dispatcher
        parent::__construct($routes, $injector, $engine);
    }

} 