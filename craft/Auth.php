<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 *
 * @author Aymeric Assier <aymeric.assier@gmail.com>
 * @date 2013-09-12
 * @version 0.1
 */
namespace craft;

abstract class Auth
{

    /**
     * Init authentication session
     */
    public static function init()
    {
        if(empty($_SESSION['craft.auth'])){
            static::logout();
        }
    }

    /**
     * Is logged in
     */
    public static function logged()
    {
        return isset($_SESSION['craft.auth']['logged'])
            ? $_SESSION['craft.auth']['logged']
            : false;
    }

    /**
     * Get rank
     */
    public static function rank()
    {
        return isset($_SESSION['craft.auth']['rank'])
            ? $_SESSION['craft.auth']['rank']
            : 0;
    }

    /**
     * Get stored user
     */
    public static function user()
    {
        return isset($_SESSION['craft.auth']['user'])
            ? unserialize($_SESSION['craft.auth']['user'])
            : null;
    }

    /**
     * Log user in
     * @param int  $rank
     * @param null $user
     */
    public static function login($rank = 1, $user = null)
    {
        $_SESSION['craft.auth'] = [
            'logged' => true,
            'rank' => (int)$rank,
            'user' => serialize($user)
        ];
    }

    /**
     * Log user out
     */
    public static function logout()
    {
        $_SESSION['craft.auth'] = [
            'logged' => false,
            'rank' => 0,
            'user' => serialize(null)
        ];
    }

}