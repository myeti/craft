<?php

namespace Craft\Orm;

use Craft\Reflect\Annotation;

class Bag implements BagInterface
{

    /** @var \PDO */
    protected $pdo;

    /** @var string */
    protected $prefix;

    /** @var Native\Builder */
    protected $builder;

    /** @var array */
    protected $map = [];


    /**
     * Load pdo
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo, $prefix = null)
    {
        $this->pdo = $pdo;
        $this->prefix = $prefix;
        $this->builder = new Native\Builder;
    }


    /**
     * Get inner pdo
     * @return \PDO
     */
    public function pdo()
    {
        return $this->pdo;
    }


    /**
     * Map entity class
     * @param $entity
     * @param $class
     */
    public function map($entity, $class)
    {
        $this->map[$entity] = $class;
        return $this;
    }


    /**
     * Create from models
     * @return bool
     */
    public function build()
    {
        $valid = true;
        foreach($this->map as $entity => $class) {

            // get props
            $fields = get_class_vars($class);
            foreach($fields as $prop => $null) {
                $fields[$prop] = [
                    'type'      => Annotation::property($class, $prop, 'var') ?: 'string',
                    'primary'   => ($prop == 'id')
                ];
            }

            // create entity
            $valid &= (bool)$this->create($entity, $fields);
        }

        return $valid;
    }


    /**
     * Get lazy entity
     * @param $entity
     * @return Bag\Bean
     */
    public function get($entity)
    {
        $class = isset($this->map[$entity]) ? $this->map[$entity] : null;
        return $this->delegate($this->pdo, $this->prefix . $entity, $class);
    }


    /**
     * Create entity
     * @param $entity
     * @param array $fields
     * @return \PDOStatement
     */
    public function create($entity, array $fields)
    {
        // make sql
        $sql = $this->builder->create($this->prefix . $entity, $fields);
        return $this->query($sql)->rowCount();
    }


    /**
     * Clear entity
     * @param $entity
     * @return int
     */
    public function clear($entity)
    {
        $sql = $this->builder->clear($this->prefix . $entity);
        return $this->query($sql)->rowCount();
    }


    /**
     * Drop entity
     * @param $entity
     * @return int
     */
    public function wipe($entity)
    {
        $sql = $this->builder->wipe($this->prefix . $entity);
        return $this->query($sql)->rowCount();
    }


    /**
     * Run custom query
     * @param $statement
     * @return \PDOStatement
     * @throws Error\SQLException
     */
    public function query($statement)
    {
        $query = $this->pdo->query($statement);
        if(!$query) {
            debug($statement, $this->pdo->errorInfo());
            throw new Error\SQLException($this->pdo->errorInfo());
        }

        return $query;
    }


    /**
     * Shorthand to get()
     * @param $entity
     * @return Bag\Bean
     */
    public function __invoke($entity)
    {
        return $this->get($entity);
    }


    /**
     * Create Candy bean
     * @param $entity
     * @return Candy
     */
    protected function delegate(\PDO $pdo, $entity, $class = null)
    {
        return new Candy($pdo, $entity, $class);
    }

}