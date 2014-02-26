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

use Craft\Data\ScopeProvider;

interface Mapper
{

    /**
     * Map model classes
     * @param array $models [key => class]
     * @return mixed
     */
    public function map(array $models);


    /**
     * Check if entity exists
     * @param $entity
     * @param $where
     * @return int
     */
    public function has($entity, $where = null);


    /**
     * Get entity
     * @param $entity
     * @param $where
     * @param $sort
     * @param $limit
     * @return mixed
     */
    public function get($entity, $where = null, $sort = null, $limit = null);


    /**
     * Get entity
     * @param $entity
     * @param $where
     * @return mixed
     */
    public function one($entity, $where = null);


    /**
     * Set entity data
     * @param $entity
     * @param $where
     * @param $data
     * @return mixed
     */
    public function set($entity, $data, $where = null);


    /**
     * Drop entity
     * @param $entity
     * @param $where
     * @return bool
     */
    public function drop($entity, $where = null);


    /**
     * Arbitrary query
     * @param $input
     * @return mixed
     */
    public function query($input);


    /**
     * Create a backup
     * @param $filename string
     * @return bool
     */
    public function backup($filename);

}