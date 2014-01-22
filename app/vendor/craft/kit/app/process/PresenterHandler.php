<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\app\process;

use craft\kit\app\ContextHandler;
use craft\kit\view\Template;
use craft\kit\app\context\View;
use craft\kit\app\Context;

class PresenterHandler extends ContextHandler
{

    /**
     * Get handler name
     * @return string
     */
    public function name()
    {
        return 'presenter';
    }


    /**
     * Handle an give back the env
     * @param Context $context
     * @return Context
     */
    public function handleContext(Context $context)
    {
        // can render ?
        if(!$context->service and !empty($context->action->metadata['render'])){

            // create view
            $view = new View();
            $view->template = $context->action->metadata['render'] . '.php';
            $view->data = is_array($context->action->data) ? $context->action->data : [];

            // compile content
            $view->content = Template::forge($view->template, $view->data);

            // update env
            $context->view = $view;

            // display content
            echo $view->content;
        }

        return $context;
    }

}