<?php

namespace Craft\Box\Native;

use Craft\Box\Provider\SessionProvider;
use Craft\Data\ArrayCollection;
use Craft\Data\Provider;

class Flash implements Provider
{

    /** @var SessionProvider */
    protected $session;


    /**
     * Bind to session
     */
    public function __construct()
    {
        $this->session = new Session\Storage('_craft/flash');
    }


    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->session->has($key);
    }

    /**
     * Consume element
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        $message = $this->session->get($key, $fallback);
        $this->drop($key);
        return $message;
    }

    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $this->session->set($key, $value);
    }

    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        $this->session->drop($key);
    }

}