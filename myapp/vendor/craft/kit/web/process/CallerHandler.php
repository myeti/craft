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
use craft\box\meta\Object;
use craft\kit\web\Context;
use craft\kit\web\ContextHandler;

class CallerHandler extends ContextHandler
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
     * @param Context $context
     * @return Context
     */
    public function handleContext(Context $context)
    {
        // is valid action
        if($context->action->callable instanceof \Closure) {

            // context wiring
            if($context->action->type == 'class_method' and Object::hasTrait($context->action->callable[0], 'craft\kit\web\NeedContext')) {
                $context->action->callable[0]->context = &$context;
            }

            // then call it !
            $context->action->data = Action::call($context->action->callable, $context->route->args);

        }

        return $context;
    }

}