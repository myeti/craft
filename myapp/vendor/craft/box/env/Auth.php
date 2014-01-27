<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\env;

use craft\box\data\StaticProvider;
use craft\box\data\SerializedRepository;

abstract class Auth extends StaticProvider
{

    /**
     * Start auth
     * @return array;
     */
    protected static function createInstance()
    {
        return new SerializedRepository($_SESSION, 'craft.auth');
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
        return static::get('user');
    }

    /**
     * Check credential
     * @param int $rank
     * @return bool
     */
    public static function check($rank = 1)
    {
        return static::rank() >= $rank;
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
        static::set('user', $user);
    }

    /**
     * Log user out
     */
    public static function logout()
    {
        static::drop('logged');
        static::drop('rank');
        static::drop('user');
    }

}