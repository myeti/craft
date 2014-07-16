<?php

namespace Craft\App;

trait Stamp
{

    /** @var array */
    protected $stamps = [];


    /**
     * Stamp label
     * @param string $label
     * @param mixed $value
     * @return $this
     */
    public function stamp($label, $value = true)
    {
        $this->stamps[$label] = $value;
        return $this;
    }


    /**
     * Is stamped
     * @param string $label
     * @return mixed
     */
    public function is($label)
    {
        return isset($this->stamps[$label])
            ? $this->stamps[$label]
            : false;
    }

} 