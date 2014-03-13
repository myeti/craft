<?php

namespace Craft\Orm;

trait Model
{

    /**
     * Get entity
     * @return mixed
     */
    public static function get()
    {
        return Syn::get(static::entity());
    }


    /**
     * Get many entities
     * @param array $where
     * @param null $sort
     * @param null $limit
     * @return array
     */
    public static function all(array $where = [], $sort = null, $limit = null)
    {
        return Syn::all(static::entity(), $where, $sort, $limit);
    }


    /**
     * Get one entities
     * @param array $where
     * @return mixed
     */
    public static function one(array $where = [])
    {
        return Syn::one(static::entity(), $where);
    }


    /**
     * Save entity
     * @param $data
     * @return int
     */
    public static function save($data)
    {
        return Syn::save(static::entity(), $data);
    }


    /**
     * Drop entity
     * @param $id
     * @return int
     */
    public static function drop($id)
    {
        return Syn::drop(static::entity(), $id);
    }


    /**
     * Get entity name
     * @return string
     */
    protected static function entity()
    {
        static $name;

        if(!$name) {
            $ns = explode('\\', get_called_class());
            $name = strtolower(end($ns));
        }

        return $name;
    }

} 