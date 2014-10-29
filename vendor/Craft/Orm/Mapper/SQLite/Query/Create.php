<?php

namespace Craft\Orm\Mapper\SQLite\Query;

use Craft\Orm\Query\Create as NativeCreate;

class Create extends NativeCreate
{

    /** @var array */
    protected $syntax = [
        'primary'       => 'PRIMARY KEY AUTOINCREMENT',
        'null'          => 'NOT NULL',
        'default'       => 'DEFAULT',
    ];

}