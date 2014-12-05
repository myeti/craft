<?php

namespace Craft\Orm\Query;

use Craft\Orm\Query;

class Select extends Query
{

    use Clause\Where;
    use Clause\Sort;
    use Clause\Limit;

    /** @var array */
    protected $fields = [];


    /**
     * Set table
     * @param string $table
     */
    public function __construct($table, array $fields = [])
    {
        if(!$fields) {
            $fields = ['*'];
        }

        $this->table = $table;
        $this->fields = $fields;
    }


    /**
     * Select fields
     * @param $fields
     * @return $this
     */
    public function select(...$fields)
    {
        $this->fields = $fields;
        return $this;
    }


    /**
     * Generate sql & values
     * @return array
     */
    public function compile()
    {
        $sql = $values = [];

        $sql[] = 'SELECT ' . implode(', ', $this->fields);
        $sql[] = 'FROM `' . $this->table . '`';

        $this->compileWhere($sql, $values);
        $this->compileSort($sql);
        $this->compileLimit($sql);

        $sql = implode("\n", $sql);

        return [$sql, $values];
    }


    /**
     * Execute query
     * @return bool|array
     */
    protected function execute()
    {
        // compile query
        list($sql, $values) = $this->compile();

        // prepare statement
        if($sth = $this->pdo->prepare($sql)) {

            // execute it
            $sth->execute($values);

            // return models or objects
            return ($this->model)
                ? $sth->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->model)
                : $sth->fetchAll(\PDO::FETCH_OBJ);
        }

        return false;
    }


    /**
     * Alias of limit(1) + apply()
     */
    public function one()
    {
        $this->limit(1);
        if($entities = $this->apply()) {
            return current($entities);
        }

        return false;
    }


    /**
     * Alias of apply()
     */
    public function find()
    {
        return $this->apply();
    }

}