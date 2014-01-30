<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Handler;

use Craft\App\Handler;
use Craft\App\Roadmap;
use Craft\Box\Meta\Resolver as MetaResolver;

class Caller extends Handler
{

    /**
     * Get handler name
     * @return string
     */
    public function name()
    {
        return 'caller';
    }


    /**
     * Handle an give back the env
     * @param Roadmap $roadmap
     * @return Roadmap
     */
    public function handleRoadmap(Roadmap $roadmap)
    {
        // is valid action
        if($roadmap->draft->callable instanceof \Closure) {

            // then call it !
            $roadmap->draft->data = MetaResolver::call($roadmap->draft->callable, $roadmap->route->args);

        }

        return $roadmap;
    }

}