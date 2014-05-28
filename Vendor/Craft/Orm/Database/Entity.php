<?php

namespace Craft\Orm\Database;

use Craft\Orm\Database;

class Entity
{

    /** @var Database */
    protected $db;

    /** @var string */
    protected $name;

    /** @var string */
    protected $model;

    /** @var Entity\Query */
    protected $query;


    /**
     * Ini entity mapper
     * @param Database $db
     * @param string $entity
     * @param string $model
     */
    public function __construct(Database $db, $entity, $model = null)
    {
        $this->db = $db;
        $this->entity = $entity;
        $this->model = $model;
        $this->query = new Entity\Query($entity);
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
        return $this->db->query($sql, $values, $this->model);
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
        $this->db->query($sql, $values);
        return $this->db->pdo()->lastInsertId();
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
        return $this->db->query($sql, $values);
    }


    /**
     * Drop entity
     * @return int
     */
    public function drop()
    {
        // build query
        $this->query->delete();
        list($sql, $values) = $this->query->generate();

        // execute
        return $this->db->query($sql, $values);
    }

} 