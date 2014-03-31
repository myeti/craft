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

use Craft\Orm\Bag;

class SQLite extends Bag
{

    /**
     * Init for sqlite
     * @param string $filename
     */
    public function __construct($filename)
    {
        $pdo = new \PDO('sqlite:' . $filename);
        parent::__construct($pdo);
        $this->builder = new SQLite\Builder;
    }

}