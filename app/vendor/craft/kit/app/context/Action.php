<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\app\context;

class Action
{

    /** @var callable */
    public $callable;

    /** @var array */
    public $metadata = [];

    /** @var string */
    public $type;

    /** @var array */
    public $args = [];

    /** @var mixed */
    public $data;

} 