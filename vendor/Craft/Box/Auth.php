<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box;

use Craft\Orm\Syn;

abstract class Auth
{

    /**
     * Log user in
     */
    public static function login($rank = 1, $user = null)
    {
        static::storage()->set('rank', $rank);
        static::storage()->set('user', $user);
    }


    /**
     * Get rank
     * @param int $compare
     * @return int|bool
     */
    public static function rank($compare = 0)
    {
        $rank = static::storage()->get('rank', 0);
        return $compare ? ($compare >= $rank) : $rank;
    }


    /**
     * Get user object
     * @return mixed
     */
    public static function user()
    {
        return static::storage()->get('user');
    }


    /**
     * Log user out
     */
    public static function logout()
    {
        static::storage()->drop('rank');
        static::storage()->drop('user');
    }


    /**
     * Attempt native basic login
     * @param string $model
     * @param string $username
     * @param string $password
     * @return bool|mixed
     */
    public static function basic($model, $username, $password)
    {
        // prepare data
        $data = compact('username', 'password');
        $data['password'] = sha1($data['password']);

        // get user from syn
        if($user = Syn::one($model, $data)) {
            static::login(1, $user);
            return $user;
        }

        return false;
    }


    /**
     * Singleton session instance
     * @return Session\Storage
     */
    protected static function storage()
    {
        static $instance;
        if(!$instance) {
            $instance = new Session\Storage('app/auth');
        }

        return $instance;
    }

}