<?php

namespace Craft\Data;

class Json extends \ArrayObject
{

    /**
     * Format json output
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getArrayCopy(), JSON_PRETTY_PRINT);
    }

} 