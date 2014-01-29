<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\data;

abstract class Collection
{

    /**
     * Get first element of array
     * @param $array
     * @return mixed
     */
    public static function first(array &$array)
    {
        return reset($array);
    }


    /**
     * Get last element of array
     * @param $array
     * @return mixed
     */
    public static function last(array &$array)
    {
        return end($array);
    }


    /**
     * Get value or null, don't throw error
     * @param $array
     * @param $key
     * @param null $fallback
     * @return null
     */
    public static function get($array, $key, $fallback = null)
    {
        return isset($array[$key]) ? $array[$key] : $fallback;
    }


    public static function random($array, $num = 1)
    {
        if($num == 1) {
            $key = array_rand($array);
            return $key;
        }
    }


    /**
     * Sort array by column names and directions
     * Ex : $sorted = Collection::sort($array, ['name' => SORT_DESC]);
     * @param array $array
     * @param array $by
     * @return mixed
     */
    public static function sort(array $array, array $by)
    {
        // init
        $columns = [];
        foreach($by as $column => $dir) {
            $columns[$column] = [];
        }

        // sort
        foreach($array as $key => $line) {
            foreach($line as $column => $value) {

                // apply sort ?
                if(isset($by[$column])) {
                    $columns[$column][$key] = $value;
                }

            }
        }

        // create full args
        $args = [];
        foreach($columns as $name => $data) {
            $args[] = $data;        // column name
            $args[] = $by[$name];  // direction
        }
        $args[] = $array;

        // apply multisort
        $ouput = call_user_func_array('array_multisort', $args);

        return $ouput;
    }


    /**
     * Filter keys using type (string, int, scalar...)
     * @param array $array
     * @param $type
     * @throws \InvalidArgumentException
     * @return array
     */
    public static function filterKeys(array $array, $type)
    {
        // no checking function
        if(!function_exists('is_' . $type)) {
            throw new \InvalidArgumentException('Function "is_' . $type . '" does not exist.');
        }

        // apply filter
        foreach($array as $key => $value) {
            if(!call_user_func('is_' . $type, $key)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

} 