<?php

namespace Craft\Orm;

use Craft\Reflect\Annotation;

class Database
{

    /** @var \PDO */
    protected $pdo;

    /** @var array */
    protected $models = [];

    /** @var Database\Entity[] */
    protected $entities = [];

    /** @var Database\Builder */
    protected $builder;


    /**
     * Init base with pdo
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->builder = new Database\Builder;
    }


    /**
     * Get pdo instance
     * @return \PDO
     */
    public function pdo()
    {
        return $this->pdo;
    }


    /**
     * Map entities to models
     * @param array $models
     * @return $this
     */
    public function map(array $models)
    {
        $this->models = array_merge($this->models, $models);
        return $this;
    }


    /**
     * Get model from entity
     * @param string $entity
     * @return bool|string
     */
    public function model($entity)
    {
        return isset($this->models[$entity])
            ? $this->models[$entity]
            : false;
    }


    /**
     * Get entity from model
     * @param string $model
     * @return bool|string
     */
    public function entity($model)
    {
        return array_search($model, $this->models);
    }


    /**
     * User custom query
     * @param string $sql
     * @param array $data
     * @param string $entity
     * @throws \PDOException
     * @return int|object[]
     */
    public function query($sql, array $data = [], $entity = null)
    {
        // check query
        $stm = $this->pdo->prepare($sql);
        if(!$stm) {
            throw new \PDOException($stm->errorInfo());
        }

        // execute
        $stm->execute($data);

        // select type
        if($stm->columnCount()) {
            return $entity
                ? $stm->fetchAll(\PDO::FETCH_CLASS, $this->entities[$entity])
                : $stm->fetchAll(\PDO::FETCH_OBJ);
        }

        // exec type
        return $stm->rowCount();
    }


    /**
     * Get entity mapper
     * @param string $entity
     * @return Database\Entity
     */
    public function get($entity)
    {
        // create entity
        if(!isset($this->entities[$entity])) {
            $model = $this->model($entity);
            $this->entities[$entity] = new Database\Entity($this, $entity, $model);
        }

        return $this->entities[$entity];
    }


    /**
     * Build entities in db
     * @return bool
     */
    public function build()
    {
        $valid = true;
        foreach($this->models as $entity => $model) {

            // get props
            $fields = get_class_vars($model);
            foreach($fields as $prop => $null) {
                $fields[$prop] = [
                    'type'      => Annotation::property($model, $prop, 'var') ?: 'string',
                    'primary'   => ($prop == 'id')
                ];
            }

            // create query
            $sql = $this->builder->create($entity, $fields);

            // execute query
            $valid &= $this->query($sql);
        }

        return $valid;
    }


    /**
     * Clear entity data
     * @param string $entity
     * @return int
     */
    public function clear($entity)
    {
        // create query
        $sql = $this->builder->clear($entity);

        // execute query
        return $this->query($sql);
    }


    /**
     * Destruct entity
     * @param $entity
     * @return int
     */
    public function wipe($entity)
    {
        // create query
        $sql = $this->builder->wipe($entity);

        // execute query
        return $this->query($sql);
    }

} 