<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm;

trait Model
{


    /**
     * Get entity
     * @return Entity
     */
    public static function query()
    {
        return Syn::get(get_called_class());
    }


    /**
     * Get many entities
     * @param array $where
     * @param int $sort
     * @param mixed $limit
     * @return static[]
     */
    public static function find(array $where = [], $sort = null, $limit = null)
    {
        return Syn::find(get_called_class(), $where, $sort, $limit);
    }


    /**
     * Get one entity
     * @param $where
     * @return static
     */
    public static function one($where)
    {
        return Syn::one(get_called_class(), $where);
    }


    /**
     * Save entity
     * @param mixed $data
     * @return int
     */
    public static function save($data)
    {
        return Syn::save(get_called_class(), $data);
    }


    /**
     * Drop entity
     * @param $where
     * @return int
     */
    public static function drop($where)
    {
        return Syn::drop(get_called_class(), $where);
    }


    /**
     * Clear entity
     * @return int
     */
    public static function clear()
    {
        return Syn::clear(get_called_class());
    }


    /**
     * Many relation
     * @param $entity
     * @param $foreign
     * @param string $local
     * @return mixed
     */
    protected function _many($entity, $foreign, $local = 'id')
    {
        return Syn::get($entity)->read()->where($foreign, $this->{$local})->find();
    }


    /**
     * One relation
     * @param $entity
     * @param $local
     * @param string $foreign
     * @return mixed
     */
    protected function _one($entity, $local, $foreign = 'id')
    {
        return Syn::get($entity)->read()->where($foreign, $this->{$local})->one();
    }

} 