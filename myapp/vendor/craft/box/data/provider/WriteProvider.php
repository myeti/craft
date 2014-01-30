<?php
/**
 * Created by PhpStorm.
 * User: Aymeric
 * Date: 21.01.14
 * Time: 12:02
 */

namespace Craft\Box\Data\Provider;


interface WriteProvider
{

    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value);

    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key);

} 