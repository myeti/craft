<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm\Adapter\SQLite;

use Craft\Orm\Database\Builder as NativeBuilder;

class Builder extends NativeBuilder
{

    /**
     * Fix <autoincrement> syntax
     */
    public function __construct()
    {
        $this->syntax['primary'] = 'PRIMARY KEY AUTOINCREMENT';
    }

} 