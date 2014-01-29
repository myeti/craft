<?php

namespace craft\box\data;

class SilentArray extends \ArrayObject
{

    /**
     * Silent get : does not throw error
     * @param mixed $index
     * @return mixed|null
     */
    public function offsetGet($index)
    {
        return isset($this[$index]) ? parent::offsetGet($index) : null;
    }

} 