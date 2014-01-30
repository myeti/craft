<?php
/**
 * Created by PhpStorm.
 * User: Aymeric
 * Date: 21.01.14
 * Time: 12:02
 */

namespace Craft\Box\Data\Provider;


interface ReadProvider
{

    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key);

    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null);

} 