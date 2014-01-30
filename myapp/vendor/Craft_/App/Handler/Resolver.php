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
use Craft\App\Roadmap\Draft;
use Craft\Box\Meta\Resolver as MetaResolver;

/**
 * Class Builder
 * Build and call action with the Route
 */
class Resolver extends Handler
{

    /**
     * Get handler name
     * @return string
     */
    public function name()
    {
        return 'resolver';
    }


    /**
     * Handle env
     * @param Roadmap $roadmap
     * @return Roadmap
     */
    public function handleRoadmap(Roadmap $roadmap)
    {
        // resolve action
        list($callable, $metadata, $type) = MetaResolver::resolve($roadmap->route->target);

        // create action structure
        $draft = new Draft();
        $draft->callable = $callable;
        $draft->metadata = $metadata;
        $draft->type = $type;
        $draft->args = $roadmap->route->args;

        // push roadmap as last arg
        $draft->args[] = ref($roadmap);

        // update env
        $roadmap->draft = $draft;

        return $roadmap;
    }

}