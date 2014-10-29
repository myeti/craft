<?php

namespace Craft\Orm\Mapper\SQLite\Query;

use Craft\Orm\Query\Truncate as NativeTruncate;

class Truncate extends NativeTruncate
{

    /**
     * Generate sql & values
     * @return array [sql, values]
     */
    public function compile()
    {
        $sql =  'DELETE FROM `' . $this->table . '`;';

        return [$sql, []];
    }

}