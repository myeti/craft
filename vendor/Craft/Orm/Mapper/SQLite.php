<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm\Mapper;

use Craft\Orm\Mapper;

class SQLite extends Mapper
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
    }


    /**
     * Add entity
     * @param string $name
     * @param string $model
     * @return SQLite\Entity
     */
    public function add($name, $model = null)
    {
        $this->entities[$name] = new SQLite\Entity($this->pdo, $name, $model);
        return $this;
    }


    /**
     * Create entity from model
     * @param string $model
     * @return $this
     */
    public function map($model)
    {
        $entity = SQLite\Entity::from($model, $this->pdo);
        $this->entities[$entity->name] = $entity;

        return $this;
    }


    /**
     * Build database
     * @return $this
     */
    public function build()
    {
        foreach($this->entities as $entity) {

            // create entity
            $query = new SQLite\Query\Create($entity->name);
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

} 