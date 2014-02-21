<?php

namespace Craft\Data\Repository;

abstract class ScalarReplica extends Scalar
{

    /**
     * Replicate inner data into external source
     * @return mixed
     */
    abstract public function replicate();


    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        parent::set($key, $value);
        $this->replicate();
    }


    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        parent::drop($key);
        $this->replicate();
    }

} 