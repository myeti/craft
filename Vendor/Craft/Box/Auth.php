<?php

namespace Craft\Box;

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