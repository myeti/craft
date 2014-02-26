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

use craft\data\Paginator;
use Craft\Orm\Mapper;
use Craft\Orm\Mapper\NativeMapper;
use Craft\Orm\Mapper\LipsumMapper;
use Craft\Orm\Pdo\MySQL;
use Craft\Orm\Pdo\SQLite;

abstract class Syn
{

    /**
     * Mapper singleton, setup Lipsum as default mapper
     * @param Mapper $mapper
     * @return Mapper
     */
    public static function mapper(Mapper $mapper = null)
    {
        static $instance;
        if($mapper) {
            $instance = $mapper;
        }
        elseif(!$instance) {
            $instance = new Mapper\LipsumMapper();
        }

        return $instance;
    }


    /**
     * Helper : init mysql
     * @param string $dbname
     * @param array $config
     */
    public static function mysql($dbname, array $config = [])
    {
        // merge config with defaults
        $config = $config + [
                'host' => '127.0.0.1',
                'user' => 'root',
                'pass' => '',
                'prefix' => '',
                'create' => true
            ];

        // init pdo
        $pdo = new Connector\MySQL($config['host'], $config['user'], $config['pass']);
        $pdo->open($dbname, $config['create']);

        // init mapper
        $mapper = new Mapper\NativeMapper($pdo, $config['prefix']);

        static::mapper($mapper);
    }


    /**
     * Helper : init sqlite
     * @param $filename
     * @param null $prefix
     */
    public static function sqlite($filename, $prefix = null)
    {
        // init pdo & mapper
        $pdo = new Connector\SQLite($filename);
        $mapper = new Mapper\NativeMapper($pdo, $prefix);

        static::mapper($mapper);
    }


    /**
     * Register models
     * @param  array $models
     * @return $this
     */
    public static function map(array $models)
    {
        return static::mapper()->map($models);
    }


    /**
     * Execute a custom sql request
     * @param  string $query
     * @param  string $cast
     * @return array
     */
    public static function query($query, $cast = null)
    {
        return static::mapper()->query($query, $cast);
    }


    /**
     * Count items in collection
     * @param $alias
     * @param $where
     * @return int
     */
    public static function has($alias, array $where = [])
    {
        return static::mapper()->has($alias, $where);
    }


    /**
     * Find a collection
     * @param $alias
     * @param $where
     * @param $orderBy
     * @param $limit
     * @return array
     */
    public static function get($alias, array $where = [], $orderBy = null, $limit = null)
    {
        return static::mapper()->get($alias, $where, $orderBy, $limit);
    }


    /**
     * Paginate a collection
     * @param $alias
     * @param $size
     * @param $page
     * @param $where
     * @param $sort
     * @return Paginator
     */
    public static function paginate($alias, $size, $page, array $where = [], $sort = null)
    {
        // calc boundaries
        $total = Syn::has($alias, $where);
        $from = ($size * ($page - 1)) + 1;

        // execute request with limit
        $data = Syn::get($alias, $where, $sort, [$from, $size]);
        return new Paginator($data, $size, $page, $total);
    }


    /**
     * Find a specific entity
     * @param  string $alias
     * @param  mixed $where
     * @return object|\stdClass
     */
    public static function one($alias, $where = null)
    {
        return static::mapper()->one($alias, $where);
    }


    /**
     * Box entity
     * @param string $alias
     * @param object $entity
     * @return bool
     */
    public static function set($alias, &$entity)
    {
        return static::mapper()->set($alias,$entity);
    }


    /**
     * Delete entity
     * @param  string $alias
     * @param  mixed $entity
     * @return bool
     */
    public static function drop($alias, $entity)
    {
        return static::mapper()->drop($alias, $entity);
    }


    /**
     * Create a backup of the database in sql
     * @param $filename string
     * @return bool
     */
    public static function backup($filename)
    {
        return static::mapper()->backup($filename);
    }

}