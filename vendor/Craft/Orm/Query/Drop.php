<?php

namespace Craft\Orm\Query;

use Craft\Orm\Query;

class Drop extends Query
{

    /**
     * Generate sql & values
     * @return array [sql, values]
     */
    public function compile()
    {
        $sql =  'DROP TABLE `' . $this->table . '`;';

        return [$sql, []];
    }

}