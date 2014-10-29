<?php

namespace Craft\Orm\Query\Clause;

trait Where
{

    /** @var array */
    protected $where = [];

    /** @var array */
    protected $operators = ['>', '>=', '<', '<=', '=', 'is', 'not', 'in', 'exists'];


    /**
     * Add where clause
     * @param string $expression
     * @param mixed $value
     * @return $this
     */
    public function where($expression, $value)
    {
        // parse last
        $split = explode(' ', $expression);
        $last = end($split);

        // case 1 : missing '= ?'
        if(preg_match('/^[a-zA-Z_0-9]+$/', $expression)) {
            $expression .= ' = ?';
        }
        // case 2 : missing '?'
        elseif(in_array($last, $this->operators)) {
            if(is_array($value)) {
                $placeholders = array_fill(0, count($value), '?');
                $expression .= ' (' . implode(', ', $placeholders) . ')';
            }
            else {
                $expression .= ' ?';
            }
        }

        $this->where[$expression] = $value;
        return $this;
    }


    /**
     * Compile where clause into sql
     * @param array $values
     * @return string
     */
    protected function compileWhere(&$sql, array &$values)
    {
        if($this->where) {

            $where = [];
            foreach($this->where as $exp => $data) {
                $where[] = $exp;
                if(is_array($data)) {
                    $values = array_merge($values, $data);
                }
                else {
                    $values[] = $data;
                }
            }

            $sql[] = 'WHERE ' . implode(' AND ', $where);
        }
    }

} 