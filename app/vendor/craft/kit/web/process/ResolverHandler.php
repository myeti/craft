<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\web\process;

use craft\box\meta\Action;
use craft\kit\web\Context;
use craft\kit\web\context\Action as ContextAction;
use craft\kit\web\ContextHandler;

/**
 * Class Builder
 * Build and call action with the Route
 */
class ResolverHandler extends ContextHandler
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
     * @param Context $context
     * @return mixed|void
     */
    public function handleContext(Context $context)
    {
        // resolve action
        list($callable, $metadata, $type) = Action::resolve($context->route->target);

        // create action structure
        $action = new ContextAction();
        $action->callable = $callable;
        $action->metadata = $metadata;
        $action->type = $type;
        $action->args = $context->route->args;

        // update env
        $context->action = $action;

        return $context;
    }

}