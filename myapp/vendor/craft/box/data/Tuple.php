<?php

namespace Craft\Box\Data;

class Tuple extends \ArrayObject
{

    /**
     * Array with forced type
     * @param array $data
     * @param array $types
     */
    public function __construct(array $data = [], array $types = [])
    {
        // clear keys
        $data = array_values($data);
        $types = array_values($types);

        // force type
        foreach($data as $key => $value) {
            if(isset($types[$key])) {
                settype($data[$key], $types[$key]);
            }
        }

        // bind
        $this->exchangeArray($data);
    }

} 