<?php

namespace Craft\Orm;

class Mapper
{

    /** @var \PDO */
    protected $pdo;

    /** @var Entity[] */
    protected $entities = [];


    /**
     * New Mapper
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * Add entity
     * @param string $name
     * @param string $model
     * @return Entity
     */
    public function add($name, $model = null)
    {
        $this->entities[$name] = new Entity($this->pdo, $name, $model);
        return $this;
    }


    /**
     * Create entity from model
     * @param string $model
     * @return $this
     */
    public function map($model)
    {
        $entity = Entity::from($model, $this->pdo);
        $this->entities[$entity->name] = $entity;

        return $this;
    }


    /**
     * Get entities
     * @return Entity[]
     */
    public function entities()
    {
        return $this->entities;
    }


    /**
     * Get entity by name
     * @param string $name
     * @throws \RuntimeException
     * @return Entity
     */
    public function entity($name)
    {
        if(!isset($this->entities[$name])) {
            throw new \RuntimeException('Unknown entity "' . $name . '"');
        }

        return $this->entities[$name];
    }


    /**
     * Get entity by model
     * @param string $model
     * @throws \RuntimeException
     * @return Entity
     */
    public function model($model)
    {
        foreach($this->entities as $entity) {
            if($entity->model == $model) {
                return $entity;
            }
        }

        throw new \RuntimeException('Unknown entity with model "' . $model . '"');
    }


    /**
     * Execute raw query
     * @param Query $query
     * @return mixed
     */
    public function query(Query $query)
    {
        $query->bind($this->pdo);
        return $query->apply();
    }


    /**
     * Build database
     * @return $this
     */
    public function build()
    {
        foreach($this->entities as $entity) {

            // create entity
            $query = new Query\Create($entity->name);
            $query->bind($this->pdo);

            // create fields
            foreach($entity->fields() as $field => $opts) {
                $query->set($field, $opts['type'], $opts['null'], $opts['default']);
            }

            // build entity
            $query->apply();
        }

        return $this;
    }


    /**
     * Destroy database
     * @return $this
     */
    public function destroy()
    {
        foreach($this->entities as $entity) {
            $entity->wipe();
        }

        return $this;
    }

} 