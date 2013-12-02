<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\core\handlers;

use craft\core\Context;
use craft\core\handler;

class Caller implements Handler
{

    /**
     * Handle an give back the context
     * @param Context $context
     * @return Context
     */
    public function handle(Context $context)
    {
        // is valid action
        if($context->build->action instanceof \Closure) {

            // then call it !
            $data = call_user_func_array($context->build->action, $context->route->args);
            $context->data = $data;

        }

        return $context;
    }

}