<?php

namespace Craft\Env\Adapter;

use Craft\Data\Repository;

class NativeSession extends Repository
{


    /**
     * Setup session in repository
     * @param string $base
     */
    public function __construct($base = null)
    {
        // start
        if(!session_id()) {
            ini_set('session.use_trans_sid', 0);
            ini_set('session.use_only_cookies', 1);
            ini_set("session.cookie_lifetime", 604800);
            ini_set("session.gc_maxlifetime", 604800);
            session_set_cookie_params(604800);
            session_start();
        }

        // create base repository
        if($base and !isset($_SESSION[$base])) {
            $_SESSION[$base] = [];
        }

        // set repository
        $repository = $base ? $_SESSION[$base] : $_SESSION;

        parent::__construct($repository);
    }


    /**
     * Get content
     * @param $key
     * @param null $fallback
     * @return mixed|void
     */
    public function get($key, $fallback = null)
    {
        $data = parent::get($key);

        // is serialized ?
        $unserialized = @unserialize($data);
        if($unserialized !== false or $data == 'b:0;') {
            $data = $unserialized;
        }

        return $data ?: $fallback;
    }


    /**
     * Set content
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        // need serialization ?
        if(!is_scalar($value)) {
            $value = serialize($value);
        }

        parent::set($key, $value);
    }

}