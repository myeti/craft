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

    /** @var string */
    protected $separator = '.';

    /** @var Filter[] */
    protected $filters = [];


    /**
     * Setup array and separator
     * @param array $input
     * @param array $filters
     * @internal param int $flags
     * @internal param string $separator
     */
    public function __construct(array $input = [], $filters = null)
    {
        // set filters
        $filters = is_array($filters) ? $filters : [$filters];
        foreach($filters as $filter) {
            if($filter instanceof Filter) {
                $this->filter($filter);
            }
        }

        // init array
        parent::__construct($input);
    }


    /**
     * Add data filter
     * @param Filter $filter
     */
    public function filter(Filter $filter)
    {
        $this->filters[] = $filter;
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

        $this->then();

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

        // out filter
        foreach($this->filters as $filter) {
            $value = $filter->out($value);
        }

        $this->then();

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

        // in filter
        foreach($this->filters as $filter) {
            $value = $filter->in($value);
        }

        // write
        $array[$key] = $value;

        $this->then();
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

        $this->then();
    }


    /**
     * Done event
     */
    protected function then() {}


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