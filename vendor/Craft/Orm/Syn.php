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

abstract class Syn
{

    /** DB priority */
    const MASTER = 'master';
    const SLAVE = 'slave';

    /** @var Mapper[] */
    protected static $mappers = [];

    /** @var int */
    protected static $use = self::MASTER;


    /**
     * Plug custom mapper
     * @param Mapper $mapper
     * @param string $as
     * @return Mapper
     */
    public static function load(Mapper $mapper, $as = self::MASTER)
    {
        static::$mappers[$as] = $mapper;
        return static::mapper();
    }


    /**
     * Get mapper
     * @param string $as
     * @throws \LogicException
     * @return Mapper
     */
    public static function mapper($as = null)
    {
        // set db
        if($as) {
            static::$use = $as;
        }

        // no db
        if(!isset(static::$mappers[static::$use])) {
            throw new \LogicException('No mapper [' . static::$use . '] loaded.');
        }

        return static::$mappers[static::$use];
    }


    /**
     * Map entities to models
     * @param array $models
     * @return Mapper
     */
    public static function map(array $models)
    {
        return static::mapper()->map($models);
    }


    /**
     * Get entity
     * @param $entity
     * @return Entity
     */
    public static function get($entity)
    {
        // model
        if(class_exists($entity)) {
            return static::mapper()->model($entity);
        }

        return static::mapper()->entity($entity);
    }


    /**
     * Get many entities
     * @param $entity
     * @param array $where
     * @param null $sort
     * @param null $limit
     * @return array
     */
    public static function find($entity, array $where = [], $sort = null, $limit = null)
    {
        return static::get($entity)->find($where, $sort, $limit);
    }


    /**
     * Get one entities
     * @param $entity
     * @param array $where
     * @return mixed
     */
    public static function one($entity, $where = [])
    {
        return static::get($entity)->one($where);
    }


    /**
     * Save entity
     * @param $entity
     * @param $data
     * @return int
     */
    public static function save($entity, $data)
    {
        return static::get($entity)->save($data);
    }


    /**
     * Drop entity
     * @param $entity
     * @param $where
     * @return int
     */
    public static function drop($entity, $where)
    {
        return static::get($entity)->drop($where);
    }


    /**
     * Clear entity
     * @param $entity
     * @return int
     */
    public static function clear($entity)
    {
        return static::get($entity)->clear();
    }


    /**
     * Setup mysql
     * @param string $dbname
     * @param array $config
     * @return Mapper\MySQL
     */
    public static function MySQL($dbname, array $config = [])
    {
        // create db
        $mapper = new Mapper\MySQL($dbname, $config);
        static::load($mapper);

        return $mapper;
    }


    /**
     * Setup sqlite
     * @param string $filename
     * @return Mapper\SQLite
     */
    public static function SQLite($filename)
    {
        // create db
        $mapper = new Mapper\SQLite($filename);
        static::load($mapper);

        return $mapper;
    }

}