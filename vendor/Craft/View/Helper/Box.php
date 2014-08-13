<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View\Helper;

use Craft\Box\Auth;
use Craft\Box\Flash;
use Craft\Box\Session;
use Craft\View\Helper;

class Box extends Helper
{

    /**
     * Read-only session
     * @param $key
     * @return mixed
     */
    public function session($key)
    {
        return Session::get($key);
    }


    /**
     * Read-only flash
     * @param $key
     * @return mixed
     */
    public function flash($key)
    {
        return Flash::get($key);
    }


    /**
     * Read-only auth
     * @return object
     */
    public function auth()
    {
        return (object)[
            'rank'      => Auth::rank(),
            'user'      => Auth::user()
        ];
    }

} 