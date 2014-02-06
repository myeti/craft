<?php

namespace Craft\Reflect\Resolver;

use Craft\Reflect\Action;

interface TypeResolver
{

    /**
     * Is type
     * @param mixed $input
     * @return bool
     */
    public function is($input);


    /**
     * Resolve closure with metadata
     * @param mixed $input
     * @return Action
     */
    public function resolve($input);

} 