<?php

namespace Craft\Orm\Query;

use Craft\Orm\Query;

class Delete extends Query
{

    use Clause\Where;


    /**
     * Generate sql & values
     * @return array
     */
    public function compile()
    {
        $sql = $values = [];

        $sql[] = 'DELETE FROM `' . $this->table . '`';

        $this->compileWhere($sql, $values);

        $sql = implode("\n", $sql);

        return [$sql, $values];
    }

} 