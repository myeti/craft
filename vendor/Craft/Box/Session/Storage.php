<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Session;

use Craft\Data\Collection;

class Storage implements StorageInterface
{

    /** @var string */
    protected $name;

    /** @var array */
    protected $data = [];


    /**
     * Start session
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;

        // config
        if(!session_id()) {
            ini_set('session.use_trans_sid', 0);
            ini_set('session.use_only_cookies', 1);
            ini_set("session.cookie_lifetime", 604800);
            ini_set("session.gc_maxlifetime", 604800);
            session_set_cookie_params(604800);
            session_start();
        }

        // get data
        $this->data = isset($_SESSION[$name]) ? $_SESSION[$name] : [];
    }


    /**
     * Get all data
     * @return array
     */
    public function all()
    {
        return $this->data;
    }


    /**
     * Check if data exists
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        list($item, $key) = Collection::resolve($key, $this->data);
        return isset($item[$key]);
    }


    /**
     * Get data
     * @param $key
     * @param mixed $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        list($item, $key) = Collection::resolve($key, $this->data);
        return isset($item[$key]) ? $item[$key] : $fallback;
    }


    /**
     * Store value
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        list($item, $key) = Collection::resolve($key, $this->data, true);
        $item[$key] = $value;
    }


    /**
     * Delete data
     * @param string $key
     */
    public function drop($key)
    {
        list($item, $key) = Collection::resolve($key, $this->data);

        if(isset($item[$key])) {
            unset($item[$key]);
        }
    }


    /**
     * Clear all data
     */
    public function clear()
    {
        $this->data = [];
    }


    /**
     * Save data in session
     */
    public function save()
    {
        $_SESSION[$this->name] = $this->data;
    }


    /**
     * At last, save
     */
    public function __destruct()
    {
        $this->save();
    }

} 