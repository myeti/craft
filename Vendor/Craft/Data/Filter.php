<?php

namespace Craft\Data;

interface Filter
{

    /**
     * Filter in
     * @param $data
     * @return mixed
     */
    public function in($data);

    /**
     * Filter out
     * @param $data
     * @return mixed
     */
    public function out($data);

} 