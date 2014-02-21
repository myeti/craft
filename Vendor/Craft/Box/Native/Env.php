<?php

namespace Craft\Box\Native;

use Craft\Data\Repository;

class Env extends Repository
{

    /**
     * Create provider instance
     */
    public function __construct()
    {
        parent::__construct($_ENV);
    }


    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        parent::set($key, $value);

        // replica
        $_ENV = $this->getArrayCopy();
    }


    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        parent::drop($key);

        // replica
        $_ENV = $this->getArrayCopy();
    }


    /**
     * Get or set timezone
     * @param string $timezone
     * @return string
     */
    public static function timezone($timezone = null)
    {
        if($timezone) {
            date_default_timezone_set($timezone);
        }

        return date_default_timezone_get();
    }


    /**
     * Get or set locale
     * @param $lang
     * @return string
     */
    public static function locale($lang = null)
    {
        if($lang) {
            setlocale(LC_ALL, $lang);
            locale_set_default($lang);
        }

        return locale_get_default();
    }

}