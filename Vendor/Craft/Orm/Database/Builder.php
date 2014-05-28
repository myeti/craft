<?php

namespace Craft\Orm\Database;

class Builder
{

    /** @var array */
    protected $syntax = [
        'primary'       => 'PRIMARY KEY AUTO_INCREMENT',
        'null'          => 'NOT NULL',
        'default'       => 'DEFAULT',
    ];

    /** @var array */
    protected $types = [
        'varchar'           => 'VARCHAR(255)',
        'string'            => 'VARCHAR(255)',
        'string text'       => 'TEXT',
        'string date'       => 'DATE',
        'string datetime'   => 'DATETIME',
        'int'               => 'INTEGER',
    ];

    /** @var array */
    protected $defaults = [
        'type'      => 'VARCHAR(255)',
        'primary'   => false,
        'null'      => true,
        'default'   => 'NULL'
    ];

    /**
     * Create entity
     * @param $entity
     * @param array $fields
     * @return mixed
     */
    public function create($entity, array $fields)
    {
        // write create
        $sql = 'CREATE TABLE IF NOT EXISTS `' . $entity . '` (';

        // each field
        foreach($fields as $field => $type) {

            // define opts
            $opts = is_array($type)
                ? $type + $this->defaults
                : ['type' => $type] + $this->defaults;

            $opts['type'] = trim($opts['type']);

            // parse type
            if(isset($this->types[$opts['type']])) {
                $opts['type'] = $this->types[$opts['type']];
            }

            // write field type
            $sql .= '`' . $field . '` ' . $opts['type'];

            // primary
            if($opts['primary']) {
                $opts['default'] = null;
                $sql .= ' ' . $this->syntax['primary'];
            }

            // null
            if(!$opts['null']) {
                $sql .= ' ' . $this->syntax['null'];
            }

            // default
            if($opts['default']) {
                $sql .= ' ' . $this->syntax['default'] . ' ' . $opts['default'];
            }

            // end of line
            $sql .= ',';
        }

        // close sql
        $sql = trim($sql, ',') . ');';

        return $sql;
    }


    /**
     * Remove entity
     * @param $entity
     * @return mixed
     */
    public function clear($entity)
    {
        return 'TRUNCATE `' . $entity . '`;';
    }


    /**
     * Remove entity
     * @param $entity
     * @return mixed
     */
    public function wipe($entity)
    {
        return 'DROP TABLE `' . $entity . '`;';
    }

}