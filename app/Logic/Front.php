<?php

/**
 * This is a controller, a class that contains many actions.
 * Use it to write all your business layer !
 */
namespace App\Logic;

/**
 * You can already define some metadata that will be applied
 * to all method, such as
 * - `@auth` : define the user rank required, if not enough, 403 is thrown
 * - `@render` : the template to use
 * - `@json` : render data as json
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
     * @render front.hello
     *
     * @return array
     */
    public function hello()
    {

    }


    /**
     * 404 Not found
     * @render error.404
     */
    public function lost()
    {

    }


    /**
     * 403 Forbidden
     * @render error.403
     */
    public function nope()
    {

    }

}