<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm;

abstract class Query
{

    /** @var string */
    protected $table;

    /** @var \PDO */
    protected $pdo;

    /** @var string */
    protected $model;


    /**
     * Set table
     * @param string $table
     */
    public function __construct($table)
    {
        $this->table = $table;
    }


    /**
     * Bind pdo instance and class model
     * @param \PDO $pdo
     * @param null $model
     */
    public function bind(\PDO $pdo, $model = null)
    {
        $this->pdo = $pdo;
        $this->model = $model;
    }


    /**
     * Generate sql & values
     * @return array [sql, values]
     */
    abstract public function compile();


    /**
     * Run query using PDO
     */
    public function apply()
    {
        if(!$this->pdo) {
            throw new \RuntimeException('PDO is not set');
        }

        return $this->execute();
    }


    /**
     * Execute query
     * @throws \PDOException
     * @return mixed
     */
    protected function execute()
    {
        // compile query
        list($sql, $values) = $this->compile();

        // prepare statement & execute
        if($sth = $this->pdo->prepare($sql)) {
            if($result = $sth->execute($values)) {
                return $result;
            }
        }

        // error
        $error = $this->pdo->errorInfo();
        throw new \PDOException('[' . $error[0] . '] ' . $error[2]);
    }


    /**
     * Print SQL only
     * @return string
     */
    public function __toString()
    {
        list($sql,) = $this->compile();
        return $sql;
    }

} 