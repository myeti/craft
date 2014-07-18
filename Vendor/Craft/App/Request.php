<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

use Craft\Box\Mog;

/**
 * The Request object contains
 * all the data given from http
 */
class Request
{

    /** @var float */
    public $start;

    /** @var string */
    public $query;

    /** @var array */
    public $args = [];

    /** @var callable */
    public $action;

    /** @var array */
    public $meta = [];


    /**
     * Init request
     * @param string $query
     * @param array $args
     * @param string|callable $action
     * @param array $meta
     */
    public function __construct($query = null, $args = [], $action = null, $meta = [])
    {
        $this->start = microtime(true);
        $this->query = $query;
        $this->args = $args;
        $this->action = $action;
        $this->meta = $meta;
    }


    /**
     * Generate request from globals
     */
    public static function generate()
    {
        // resolve query
        $query = Mog::query();

        // create request
        return new self($query);
    }

} 