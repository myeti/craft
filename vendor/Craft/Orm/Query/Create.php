<?php

namespace Craft\Orm\Query;

use Craft\Orm\Query;

class Create extends Query
{

    /** @var array */
    protected $fields = [];

    /** @var array */
    protected $syntax = [
        'primary'       => 'PRIMARY KEY AUTO_INCREMENT',
        'null'          => 'NOT NULL',
        'default'       => 'DEFAULT',
    ];

    /** @var array */
    protected $types = [
        'string'            => 'VARCHAR(255)',
        'string email'      => 'VARCHAR(255)',
        'string text'       => 'TEXT',
        'string date'       => 'DATE',
        'string datetime'   => 'DATETIME',
        'int'               => 'INTEGER',
        'bool'              => 'BOOLEAN',
    ];


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
     * Generate sql & values
     * @return array [sql, values]
     */
    public function compile()
    {
        // write create
        $sql = 'CREATE TABLE IF NOT EXISTS `' . $this->table . '` (';

        // each field
        foreach($this->fields as $field => $opts) {

            // convert type
            $type = trim($opts['type']);
            $type = isset($this->types[$type])
                ? $this->types[$type]
                : 'VARCHAR(255)';

            // write field type
            $sql .= "\n" .  '`' . $field . '` ' . $type;

            // primary
            if($field === 'id') {
                $opts['primary'] = true;
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
        $sql = trim($sql, ',') . "\n" . ');';

        return [$sql, []];
    }

}