<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\data;

use craft\box\FlatArray;

abstract class Auth extends FlatArray
{

    /**
     * Start auth
     */
    public static function init()
    {
        if(empty($_SESSION['craft.auth'])){
            $_SESSION['craft.auth'] = [];
        }

        static::$_data = &$_SESSION['craft.auth'];
    }

    /**
     * Is logged in
     * @return bool
     */
    public static function logged()
    {
        return (bool)static::get('logged');
    }

    /**
     * Get rank
     * @return int
     */
    public static function rank()
    {
        return (int)static::get('rank');
    }

    /**
     * Get stored user
     * @return mixed
     */
    public static function user()
    {
        $user = static::get('user');
        return $user ? unserialize($user) : null;
    }

    /**
     * Log user in
     * @param int  $rank
     * @param mixed $user
     */
    public static function login($rank = 1, $user = null)
    {
        static::set('logged', true);
        static::set('rank', (int)$rank);
        static::set('user', serialize($user));
    }

    /**
     * Log user out
     */
    public static function logout()
    {
        static::set('logged', false);
        static::set('rank', 0);
        static::set('user', serialize(null));
    }

}