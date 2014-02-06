<?php

namespace Craft\Data;

class SilentArray extends \ArrayObject
{

    /**
     * Silent get : does not throw error
     * @param mixed $index
     * @return mixed
     */
    public function offsetGet($index)
    {
        return isset($this[$index]) ? parent::offsetGet($index) : null;
    }

} 