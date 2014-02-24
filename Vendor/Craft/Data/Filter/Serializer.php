<?php

namespace Craft\Data\Filter;

use Craft\Data\Filter;

class Serializer implements Filter
{

    /**
     * Filter in
     * @param $data
     * @return mixed
     */
    public function in($data)
    {
        if(!is_scalar($data)) {
            $data = serialize($data);
        }

        return $data;
    }

    /**
     * Filter out
     * @param $data
     * @return mixed
     */
    public function out($data)
    {
        if(is_string($data)) {
            $decrypted = @unserialize($data);
            if($decrypted !== false or $data == 'b:0;') {
                $data = $decrypted;
            }
        }

        return $data;
    }

}