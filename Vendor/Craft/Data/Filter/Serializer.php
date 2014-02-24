<?php

namespace Craft\Data\Filter;

use Craft\Data\Provider;

class Serializer implements Provider
{

    /** @var Provider */
    protected $provider;


    /**
     * Set subject provider
     * @param Provider $provider
     */
    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }


    /**
     * Get all elements
     * @return array
     */
    public function all()
    {
        return $this->provider->all();
    }


    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return $this->provider->has($key);
    }


    /**
     * Filter in
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        if(!is_scalar($value)) {
            $value = serialize($value);
        }

        return $this->provider->set($key, $value);
    }


    /**
     * Filter out
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        $value = $this->provider->get($key, $fallback);

        if(is_string($value)) {
            $decrypted = @unserialize($value);
            if($decrypted !== false or $value == 'b:0;') {
                $value = $decrypted;
            }
        }

        return $value;
    }


    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        return $this->provider->drop($key);
    }


    /**
     * Clear all elements
     * @return bool
     */
    public function clear()
    {
        return $this->provider->clear();
    }
}