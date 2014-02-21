<?php

namespace Craft\Data\Repository;

use Craft\Data\Repository;

class Scalar extends Repository
{

    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        $data = parent::get($key, $fallback);

        $decrypted = @unserialize($data);
        if($decrypted !== false or $data == 'b:0;') {
            $data = $decrypted;
        }

        return $data;
    }


    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        if(!is_scalar($value)) {
            $value = serialize($value);
        }

        parent::set($key, $value);
    }

} 