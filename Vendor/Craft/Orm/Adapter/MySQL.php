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
     * @param array $config
     */
    public function __construct($dbname, array $config = [])
    {
        // default connector
        $config += [
            'host'      => 'localhost',
            'username'  => 'root',
            'password'  => null,
            'prefix'    => null,
        ];

        // create pdo instance
        $connector = 'mysql:host=' . $config['host'] . ';dbname=' . $dbname;
        $pdo = new \PDO($connector, $config['username'], $config['password']);

        // init bag
        parent::__construct($pdo, $config['prefix']);
    }

} 