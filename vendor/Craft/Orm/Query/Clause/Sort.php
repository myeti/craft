<?php

namespace Craft\Orm\Query\Clause;

trait Sort
{

    /** @var array */
    protected $sort = [];


    /**
     * Sort data
     * @param $field
     * @param int $sort
     * @return $this
     */
    public function sort($field, $sort = SORT_ASC)
    {
        $this->sort[$field] = $sort;
        return $this;
    }


    /**
     * Compile sort clause into sql
     * @param $sql
     */
    protected function compileSort(&$sql)
    {
        if($this->sort) {
            $sort = [];
            foreach($this->sort as $field => $sens) {
                $sort[] = '`' . $field . '` ' . ($sens == SORT_DESC ? 'DESC' : 'ASC');
            }
            $sql[] = 'ORDER BY ' .implode(', ', $sort);
        }
    }

} 