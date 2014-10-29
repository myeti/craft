<?php

namespace Craft\Orm;

use Craft\Kit\Metadata;

class Entity
{

    /** @var string */
    public $name;

    /** @var string */
    public $model;

    /** @var array */
    protected $fields = [];

    /** @var \PDO */
    protected $pdo;


    /**
     * New entity
     * @param \PDO $pdo
     * @param string $name
     * @param string $model
     */
    public function __construct(\PDO $pdo, $name, $model = null)
    {
        $this->pdo = $pdo;
        $this->name = $name;
        $this->model = $model;
    }


    /**
     * Set field
     * @param string $field
     * @param string $type
     * @param bool $null
     * @param string $default
     * @return $this
     */
    public function set($field, $type = 'string', $null = true, $default = null)
    {
        $this->fields[$field] = [
            'type' => $type,
            'null' => $null,
            'default' => $default,
        ];

        return $this;
    }


    /**
     * Get fields definition
     * @return array
     */
    public function fields()
    {
        return $this->fields;
    }


    /**
     * Init select query
     * @param $fields
     * @return Query\Select
     */
    public function read(...$fields)
    {
        $query = new Query\Select($this->name, $fields);
        $query->bind($this->pdo, $this->model);

        return $query;
    }


    /**
     * Init insert query
     * @param array $data
     * @return Query\Insert
     */
    public function create(array $data)
    {
        $query = new Query\Insert($this->name, $data);
        $query->bind($this->pdo, $this->model);

        return $query;
    }


    /**
     * Init update query
     * @param array $data
     * @return Query\Update
     */
    public function edit(array $data)
    {
        $query = new Query\Update($this->name, $data);
        $query->bind($this->pdo, $this->model);

        return $query;
    }


    /**
     * Init delete query
     * @return Query\Delete
     */
    public function delete()
    {
        $query = new Query\Delete($this->name);
        $query->bind($this->pdo, $this->model);

        return $query;
    }


    /**
     * Get many entities
     * @param array $where
     * @param array|string $sort
     * @param array|string $limit
     * @return bool|mixed
     */
    public function find(array $where = [], $sort = null, $limit = null)
    {
        $query = $this->read('*');

        foreach($where as $cond => $value) {
            $query->where($cond, $value);
        }

        if($sort and is_array($sort)) {
            foreach($sort as $field => $sorting) {
                $query->sort($field, $sorting);
            }
        }
        elseif($sort) {
            $query->sort($sort);
        }

        if($limit and is_array($limit)) {
            $query->limit($limit[0], $limit[1]);
        }
        elseif($limit) {
            $query->limit($limit);
        }

        return $query->find();
    }


    /**
     * Get one entity
     * @param $where
     * @return bool|mixed
     */
    public function one($where)
    {
        $query = $this->read('*');

        if(is_array($where)) {
            foreach($where as $cond => $value) {
                $query->where($cond, $value);
            }
        }
        else {
            $query->where('id', $where);
        }

        return $query->one();
    }


    /**
     * Save entity
     * @param array|object $data
     * @return bool|int
     */
    public function save($data)
    {
        // model
        if(is_object($data) and $data instanceof $this->model) {
            $data = get_object_vars($data);
        }
        // error
        elseif(!is_array($data)) {
            throw new \RuntimeException('Data must be array or instance of ' . $this->model);
        }

        // insert
        if(empty($data['id'])) {
            return $this->create($data)->apply();
        }

        // update
        return $this->edit($data)->where('id', $data['id'])->apply();
    }


    /**
     * Delete entity
     * @param array|int $where
     * @return bool
     */
    public function drop($where)
    {
        $query = $this->delete();

        if(is_array($where)) {
            foreach($where as $cond => $value) {
                $query->where($cond, $value);
            }
        }
        else {
            $query->where('id', $where);
        }

        return $query->apply();
    }


    /**
     * Clear all data
     * DANGEROUS
     * @return bool
     */
    public function clear()
    {
        $query = new Query\Truncate($this->name);
        $query->bind($this->pdo);

        return $query->apply();
    }


    /**
     * Wipe entity
     * DANGEROUS
     * @return bool
     */
    public function wipe()
    {
        $query = new Query\Drop($this->name);
        $query->bind($this->pdo);

        return $query->apply();
    }


    /**
     * Make entity from model
     * @param $model
     * @return Entity
     */
    public static function from($model, \PDO $pdo)
    {
        // get name
        $name = Metadata::object($model, 'name') ?: strtolower(classname($model));

        // create node
        $entity = new static($pdo, $name, $model);

        // add properties
        $properties = get_class_vars($model);
        foreach($properties as $property => $default) {

            // get type
            $type = Metadata::property($model, $property, 'var');
            if(!$type) {
                $type = 'string';
            }

            // can be null ?
            $null = (Metadata::property($model, $property, 'null') == 'true') ? true : false;

            // add field
            $entity->set($property, $type, $null, $default);
        }

        return $entity;
    }

} 