<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Data;

class SerializedRepository extends Repository
{

    /**
     * Unserialize content
     * @param $key
     * @param null $fallback
     * @return mixed|void
     */
    public function get($key, $fallback = null)
    {
        $data = parent::get($key);
        return $data ? unserialize($data) : $fallback;
    }

    /**
     * Serialize content
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        return parent::set($key, serialize($value));
    }

}