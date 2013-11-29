<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\core\builder;

class Build
{

    /** @var \Closure */
    public $action;

    /** @var array */
    public $metadata = [];

}