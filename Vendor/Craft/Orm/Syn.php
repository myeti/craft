<?php

namespace Craft\Orm;

use Craft\Orm\Adapter\MySQL;
use Craft\Orm\Adapter\SQLite;

abstract class Syn
{

    /** Bags priority */
    const MASTER = 'master.db';
    const SLAVE = 'slave.db';

    /** @var BagInterface[] */
    protected static $bags = [];

    /** @var int */
    protected static $use = self::MASTER;


    /**
     * Load Bag as master
     * @param BagInterface $bag
     * @param string $as
     * @return \Craft\Orm\BagInterface
     */
    public static function load(BagInterface $bag, $as = self::MASTER)
    {
        static::$bags[$as] = $bag;
        return static::bag();
    }


    /**
     * Load Bag as master
     * @param string $as
     * @throws \LogicException
     * @return BagInterface
     */
    public static function bag($as = null)
    {
        // set Bag
        if($as) {
            static::$use = $as;
        }

        // no Bag
        if(!isset(static::$bags[static::$use])) {
            throw new \LogicException('No Bag [' . static::$use . '] loaded.');
        }

        return static::$bags[static::$use];
    }


    /**
     * Get entity
     * @param $entity
     * @return mixed
     */
    public static function get($entity)
    {
        return static::bag()->get($entity);
    }


    /**
     * Get many entities
     * @param $entity
     * @param array $where
     * @param null $sort
     * @param null $limit
     * @return array
     */
    public static function all($entity, array $where = [], $sort = null, $limit = null)
    {
        $bag = static::bag()->get($entity);

        foreach($where as $expression => $value) {
            $bag->where($expression, $value);
        }

        if($sort and is_array($sort)) {
            foreach($sort as $field => $sorting) {
                $bag->sort($field, $sorting);
            }
        }
        elseif($sort) {
            $bag->sort($sort);
        }

        if($limit and is_array($limit)) {
            $bag->limit($limit[0], $limit[1]);
        }
        elseif($limit) {
            $bag->limit($limit);
        }

        return $bag->all();
    }


    /**
     * Get one entities
     * @param $entity
     * @param array $where
     * @return mixed
     */
    public static function one($entity, array $where = [])
    {
        $bag = static::bag()->get($entity);

        foreach($where as $expression => $value) {
            $bag->where($expression, $value);
        }

        return $bag->one();
    }


    /**
     * Save entity
     * @param $entity
     * @param $data
     * @return int
     */
    public static function save($entity, $data)
    {
        // parse object
        if(is_object($data)) {
            $data = get_object_vars($data);
        }

        // insert
        if(empty($data['id'])) {
            return static::bag()->get($entity)->add($data);
        }

        // update
        return static::bag()->get($entity)->where('id', $data['id'])->set($data);
    }


    /**
     * Drop entity
     * @param $entity
     * @param $id
     * @return int
     */
    public static function drop($entity, $id)
    {
        return static::bag()->get($entity)->where('id', $id)->drop();
    }


    /**
     * Setup mysql
     * @param string $dbname
     * @param array $config
     * @return \Craft\Orm\BagInterface
     */
    public static function MySQL($dbname, array $config = [])
    {
        // create bag
        $bag = new MySQL($dbname, $config);
        static::load($bag);

        return static::bag();
    }


    /**
     * Setup mysql
     * @param string $filename
     * @return \Craft\Orm\BagInterface
     */
    public static function SQLite($filename)
    {
        $bag = new SQLite($filename);
        static::load($bag);

        return static::bag();
    }

} 