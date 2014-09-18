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
use Craft\Box\Mog\Context;

/**
 * The Request object contains
 * all the data given from context
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

    /** @var string */
    public $error;

    /** @var Context */
    public $ctx;


    /**
     * Init request
     * @param string $query
     * @param string|callable $action
     * @param array $args
     * @param array $meta
     */
    public function __construct($query = null, $action = null, $args = [], $meta = [])
    {
        $this->start = microtime(true);
        $this->query = $query;
        $this->action = $action;
        $this->args = $args;
        $this->meta = $meta;
        $this->ctx = Mog::context();
    }


    /**
     * Generate request from globals
     */
    public static function generate()
    {
        // resolve query (priority to pathinfo)
        $query = Mog::server('PATH_INFO') ?: Mog::query();
        $query = trim($query, '/');
        if(!$query) {
            $query = '/';
        }

        // create request
        return new self($query);
    }

} 