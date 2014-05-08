<?php

namespace Craft\Orm\Native;

class Builder
{

    /** @var array */
    protected $syntax = [
        'primary'       => 'primary key auto_increment',
        'null'          => 'not null',
        'default'       => 'default',
    ];

    /** @var array */
    protected $types = [
        'varchar'           => 'varchar(255)',
        'string'            => 'varchar(255)',
        'string text'       => 'text',
        'string date'       => 'date',
        'string datetime'   => 'datetime',
        'int'               => 'integer',
    ];

    /** @var array */
    protected $defaults = [
        'type'      => 'varchar(255)',
        'primary'   => false,
        'null'      => true,
        'default'   => 'null'
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
        $sql = 'create table if not exists `' . $entity . '` (';

        // each field
        foreach($fields as $field => $type) {

            // clean

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
        return 'truncate `' . $entity . '`;';
    }


    /**
     * Remove entity
     * @param $entity
     * @return mixed
     */
    public function wipe($entity)
    {
        return 'drop table `' . $entity . '`;';
    }

}