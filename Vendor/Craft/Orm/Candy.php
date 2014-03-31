<?php

namespace Craft\Orm;

use Craft\Orm\Error\SQLException;

class Candy implements CandyInterface
{

    /** @var string */
    protected $class;

    /** @var \PDO */
    protected $pdo;

    /** @var QueryInterface */
    protected $query;


    /**
     * Init entity
     * @param \PDO $pdo
     * @param QueryInterface $query
     * @param string $entity
     * @param string $class
     */
    public function __construct(\PDO $pdo, $entity, $class = null)
    {
        $this->pdo = $pdo;
        $this->class = $class;
        $this->query = new Native\Query($entity);
    }


    /**
     * Add where clause
     * @param $expression
     * @param mixed $value
     * @return $this
     */
    public function where($expression, $value = null)
    {
        call_user_func_array([$this->query, 'where'], func_get_args());
        return $this;
    }


    /**
     * Sort data
     * @param $field
     * @param int $sort
     * @return $this
     */
    public function sort($field, $sort = SORT_ASC)
    {
        $this->query->sort($field, $sort);
        return $this;
    }


    /**
     * Limit rows
     * @param int $i
     * @param int $step
     * @return $this
     */
    public function limit($i, $step = 0)
    {
        $this->query->limit($i, $step);
        return $this;
    }


    /**
     * Get many rows
     * @return $this
     */
    public function all()
    {
        // build query
        $this->query->select(['*']);
        list($sql, $values) = $this->query->generate();

        // execute
        $stm = $this->pdo->prepare($sql);
        $stm->execute($values);

        // cast
        if($this->class) {
            $stm->setFetchMode(\PDO::FETCH_CLASS, $this->class);
            return $stm->fetchAll(\PDO::FETCH_CLASS);
        }

        return $stm->fetchAll(\PDO::FETCH_OBJ);
    }


    /**
     * First row
     * @return $this
     */
    public function one()
    {
        // limit 1
        $this->query->limit(1);

        // execute
        $many = $this->all();

        return current($many);
    }


    /**
     * Set entity data
     * @param $data
     * @return int
     */
    public function add($data)
    {
        // build query
        $this->query->insert($data);
        list($sql, $values) = $this->query->generate();

        // execute
        $this->execute($sql, $values);
        return $this->pdo->lastInsertId();
    }


    /**
     * Set entity data
     * @param $data
     * @return int
     */
    public function set($data)
    {
        // build query
        $this->query->update($data);
        list($sql, $values) = $this->query->generate();

        // execute
        $stm = $this->execute($sql, $values);
        return $stm->rowCount();
    }


    /**
     * Drop entity
     */
    public function drop()
    {
        // build query
        $this->query->delete();
        list($sql, $values) = $this->query->generate();

        // execute
        $stm = $this->execute($sql, $values);
        return $stm->rowCount();
    }


    /**
     * Execute query
     * @param string $statement
     * @param array $values
     * @return \PDOStatement
     * @throws \Craft\Orm\Error\SQLException
     */
    protected function execute($statement, array $values = [])
    {
        $stm = $this->pdo->prepare($statement);
        $stm->execute($values);

        if(!$stm) {
            throw new SQLException($this->pdo->errorInfo());
        }

        return $stm;
    }

}