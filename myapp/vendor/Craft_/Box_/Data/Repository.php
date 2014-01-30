<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Data;

use Craft\Box\Data\Provider;

class Repository extends \ArrayObject implements Provider
{

    /** @var string */
    protected $separator;


    /**
     * Setup array and separator
     * @param array $input
     * @param string $separator
     */
    public function __construct(array $input = [], $separator = '.')
    {
        $this->separator = $separator;
        parent::__construct($input);
    }


    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $array = $this->resolve($key);
        return (bool)$array;
    }


    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        $array = $this->resolve($key);
        $key = $this->parse($key);
        return $array ? $array[$key] : $fallback;
    }


    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $array = &$this->resolve($key);
        $key = $this->parse($key);
        $array[$key] = $value;
    }


    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        $array = &$this->resolve($key);
        $key = $this->parse($key);
        if($array) {
            unset($array[$key]);
        }
    }


    /**
     * Resolve path to value
     * @param $namespace
     * @param bool $dig
     * @return array
     */
    protected function &resolve($namespace, $dig = false)
    {
        // parse info
        $array = &$this;
        $namespace = trim($namespace, $this->separator);

        // empty namespace
        if(!$namespace) {
            return $array;
        }

        $segments = explode($this->separator, $namespace);
        if(count($segments) < 2) {

            if(!$dig) {
                return $array;
            }

            $array[$segments[0]] = [];

            return $array;
        }

        unset($segments[count($segments)-1]);

        foreach($segments as $segment) {
            if(null !== $array and !array_key_exists($segment, $array)) {
                $array[$segment] = $dig ? [] : null;
            }

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


    /**
     * Create Repository from array
     * @param array $array
     * @param $baseKey
     * @return Repository
     */
    public static function from(array &$array, $baseKey)
    {
        if(!isset($array[$baseKey])) {
            $array[$baseKey] = [];
        }

        return new self($array[$baseKey]);
    }

}