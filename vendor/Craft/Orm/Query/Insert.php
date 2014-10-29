<?php

namespace Craft\Orm\Query;

use Craft\Orm\Query;

class Insert extends Query
{

    /** @var array */
    protected $data;


    /**
     * Set table
     * @param string $table
     * @param array $data
     */
    public function __construct($table, array $data)
    {
        $this->table = $table;
        $this->data = $data;
    }


    /**
     * Select field
     * @param string $field
     * @param mixed $value
     * @return $this
     */
    public function set($field, $value)
    {
        $this->data[$field] = $value;
        return $this;
    }


    /**
     * Generate sql & values
     * @return array
     */
    public function compile()
    {
        $sql = $values = [];

        $sql[] = 'INSERT INTO `' . $this->table . '`';

        $fields = $holders = [];
        foreach($this->data as $field => $value) {
            $fields[] = '`' . $field . '`';
            $holders[] = '?';
            $values[] = $value;
        }
        $sql[] = '(' . implode(', ', $fields) . ')';
        $sql[] = 'VALUES (' . implode(', ', $holders) . ')';

        $sql = implode("\n", $sql);

        return [$sql, $values];
    }


    /**
     * Execute query
     * @return bool
     */
    protected function execute()
    {
        if(parent::execute()) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }

} 