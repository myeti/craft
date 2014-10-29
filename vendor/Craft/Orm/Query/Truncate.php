<?php

namespace Craft\Orm\Query;

use Craft\Orm\Query;

class Truncate extends Query
{

    /**
     * Generate sql & values
     * @return array [sql, values]
     */
    public function compile()
    {
        $sql =  'TRUNCATE `' . $this->table . '`;';

        return [$sql, []];
    }

}