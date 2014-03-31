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

class MySQL extends Bag
{

    /**
     * Init for mysql
     * @param string $dbname
     * @param array $connector
     */
    public function __construct($dbname, array $connector = [])
    {
        // default connector
        $connector = $connector + [
            'host'      => 'localhost',
            'username'  => 'root',
            'password'  => null,
            'prefix'    => null,
        ];

        // create pdo instance
        $connector = 'mysql:host=' . $connector['host'] . ';dbname=' . $dbname;
        $pdo = new \PDO($connector, $connector['username'], $connector['password']);

        // init bag
        parent::__construct($pdo, $connector['prefix']);
    }

} 