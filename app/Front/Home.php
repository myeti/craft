<?php

namespace App\Front;

/**
 * This is a controller, a class that contains you website logic.
 *
 * You can define some metadata that will be applied to all method, such as :
 *
 * - `@auth` : define the user rank required, if not enough, 403 is thrown
 * - `@render html {template}` : the template to use
 * - `@render json` : render data as json
 */
class Home
{

    /**
     * Your action !
     *
     * Whatever you do in this method, if you want to return data
     * to the view, return it as an array
     *
     * The next tag set the template to use (don't add .php)
     * @render html home.page
     *
     * @return array
     */
    public function page()
    {

    }

}