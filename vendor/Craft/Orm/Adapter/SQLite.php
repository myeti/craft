<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm\Adapter;

use Craft\Orm\Database;
use Craft\Trace\Logger;

class SQLite extends Database
{

    /**
     * SQLite connector
     * @param string $filename
     */
    public function __construct($filename)
    {
        // create pdo instance
        $pdo = new \PDO('sqlite:' . $filename);
        parent::__construct($pdo);

        // custom builder`
        $this->builder = new SQLite\Builder;

        Logger::info('SQLite connected using `' . $filename . '`');
    }

} 