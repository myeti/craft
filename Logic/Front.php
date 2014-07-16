<?php

/**
 * This is a controller, a class that contains many actions.
 * Use it to write all your business layer !
 */
namespace My\Logic;

use Craft\Box\Auth;
use Craft\Box\Env;
use Craft\Box\Flash;
use Craft\Box\Mog;
use Craft\Box\Session;
use My\Model\User;

/**
 * You can already define some metadata that will be applied
 * to all method, such as
 * - `@auth` : define the user rank required, if not enough, 403 is thrown
 * - `@render` : the template to use
 */
class Front
{

    /**
     * Your action !
     *
     * Whatever you do in this method, if you want to return data
     * to the view, return it as an array
     *
     * The next tag set the template to use (don't add .php)
     * @render views/front.hello
     *
     * @return array
     */
    public function hello()
    {

    }

}