<?php

namespace Craft\Orm;

interface BagInterface
{

    /**
     * Get inner pdo
     * @return \PDO
     */
    public function pdo();

    /**
     * Map entity class
     * @param $entity
     * @param $class
     * @return $this
     */
    public function map($entity, $class);

    /**
     * Create from models
     * @return bool
     */
    public function build();

    /**
     * Get lazy entity
     * @param $entity
     * @return CandyInterface
     */
    public function get($entity);

    /**
     * Run custom query
     * @param $statement
     * @return mixed
     */
    public function query($statement);

}