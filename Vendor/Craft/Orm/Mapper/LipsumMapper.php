<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Orm\Mapper;

use Craft\Text\Lipsum;

class LipsumMapper extends AbstractMapper
{

    /**
     * Execute a custom sql request
     * @param  string $query
     * @param  string $cast
     * @return array
     */
    public function query($query, $cast = null)
    {
        return [];
    }


    /**
     * Count items in collection
     * @param  string $alias
     * @param  array $where
     * @return int
     */
    public function has($alias, $where = [])
    {
        return count($this->get($alias, $where));
    }


    /**
     * Find a collection
     * @param  string $alias
     * @param  array $where
     * @param null $orderBy
     * @param null $limit
     * @throws \PDOException
     * @return array
     */
    public function get($alias, $where = [], $orderBy = null, $limit = null)
    {
        // set max
        $max = rand(4, 20);
        if($limit) {
            $max = is_array($limit) ? $limit[1] : $limit;
        }

        // init
        if(empty($this->models[$alias])) {
            throw new \PDOException('No alias named "' . $alias . '".');
        }
        $model = $this->models[$alias];

        // get schema
        $schema = $this->schema($alias);

        // populate
        $data = [];
        foreach(range(0, $max) as $i) {

            // init & populate
            $entity = new $model();
            foreach($schema as $field => $type) {

                // int
                if($type == 'int') { $entity->{$field} = Lipsum::number(); }
                // text
                elseif($type == 'string text') { $entity->{$field} = Lipsum::text(); }
                // date
                elseif($type == 'string date') { $entity->{$field} = Lipsum::date(); }
                // string
                else { $entity->{$field} = Lipsum::line(); }

            }

            $data[] = $entity;
        }

        return $data;
    }


    /**
     * Find a specific entity
     * @param  string $alias
     * @param  mixed $where
     * @return object|\stdClass
     */
    public function one($alias, $where = null)
    {
        $data = $this->get($alias, [], null, 1);
        return current($data);
    }


    /**
     * Box entity
     * @param string $alias
     * @param object $entity
     * @param null $none
     * @return bool
     */
    public function set($alias, $entity, $none = null)
    {
        return true;
    }


    /**
     * Delete entity
     * @param  string $alias
     * @param  mixed $entity
     * @return bool|int
     */
    public function drop($alias, $entity = null)
    {
        return true;
    }


    /**
     * Create a backup of the database in sql
     * @param $filename string
     * @return bool
     */
    public function backup($filename)
    {
        return true;
    }

} 