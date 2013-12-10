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

use craft\core\Handler;
use craft\core\data\Context;
use craft\core\data\View;

class Engine implements Handler
{

    /**
     * Handle an give back the context
     * @param Context $context
     * @throws \RuntimeException
     * @return Context
     */
    public function handle(Context $context)
    {
        // can render ?
        if(!$context->service and !empty($context->build->metadata['render'])){

            // create view
            $view = new View($context->build->metadata['render'], (array)$context->data);

            // compile content
            $content = $view->compile();

            // update context
            $context->view = $view;
            $context->content = $content;

            // display content
            echo $content;

        }

        return $context;
    }

}