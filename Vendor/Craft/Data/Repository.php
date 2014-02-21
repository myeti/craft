<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data;

use Craft\Reflect\Event;

class Repository extends \ArrayObject implements Provider
{

    const SERIALIZE = 0x1;

    /** @var string */
    protected $separator;

    /** @var int */
    protected $flags;


    /**
     * Setup array and separator
     * @param array $input
     * @param int $flags
     * @param string $separator
     */
    public function __construct(array $input = [], $flags = null, $separator = '.')
    {
        // set parameters
        $this->separator = $separator;
        $this->flags = $flags;

        // init array
        parent::__construct($input);
    }


    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        // get data
        $array = $this->resolve($key);
        $key = $this->parse($key);

        return isset($array[$key]);
    }


    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        // get data
        $array = $this->resolve($key);
        $key = $this->parse($key);
        $value = isset($array[$key]) ? $array[$key] : $fallback;

        // unserialize
        if(($this->flags & self::SERIALIZE) and is_string($value)) {
            $decrypted = @unserialize($value);
            if($decrypted !== false or $value == 'b:0;') {
                $value = $decrypted;
            }
        }

        return $value;
    }


    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        // get data
        $array = &$this->resolve($key, true);
        $key = $this->parse($key);

        // serialize
        if(($this->flags & self::SERIALIZE) and !is_scalar($value)) {
            $value = serialize($value);
        }

        // write
        $array[$key] = $value;
    }


    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        // get data
        $array = &$this->resolve($key);
        $key = $this->parse($key);

        // drop
        if(isset($array[$key])) {
            unset($array[$key]);
        }
    }


    /**
     * Resolve path to value
     * @param string $namespace
     * @param bool $dig
     * @return array
     */
    protected function &resolve($namespace, $dig = false)
    {
        // parse info
        $array = &$this;
        $namespace = trim($namespace, $this->separator);
        $segments = explode($this->separator, $namespace);
        $last = end($segments);

        // one does not simply walk into Mordor
        foreach($segments as $i => $segment) {

            // is last ?
            if($segment == $last) {
                break;
            }

            // namespace does not exist
            if(!isset($array[$segment])) {

                // stop here
                if(!$dig) {
                    break;
                }

                $array[$segment] = [];

            }

            // next segment
            $array = &$array[$segment];

        }

        return $array;
    }


    /**
     * Parse key
     * @param $namespace
     * @return mixed
     */
    protected function parse($namespace)
    {
        $segments = explode($this->separator, $namespace);
        return end($segments);
    }

}