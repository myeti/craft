<?php

namespace craft\session;

abstract class Auth
{


    /**
     * Get rank
     * @return int
     */
    public static function logged()
    {
        return isset($_SESSION['craft.auth']['logged'])
            ? $_SESSION['craft.auth']['logged']
            : false;
    }


    /**
     * Get rank
     * @return int
     */
    public static function rank()
    {
        return isset($_SESSION['craft.auth']['rank'])
            ? $_SESSION['craft.auth']['rank']
            : 0;
    }


    /**
     * Get user
     * @return mixed
     */
    public static function user()
    {
        return isset($_SESSION['craft.auth']['user'])
            ? unserialize($_SESSION['craft.auth']['user'])
            : null;
    }


    /**
     * Log user in
     * @param $rank
     * @param null $user
     */
    public static function login($rank, $user = null)
    {
        $_SESSION['craft.auth'] = [
            'logged' => true,
            'rank' => $rank,
            'user' => serialize($user),
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
            'user' => serialize(null),
        ];
    }

}