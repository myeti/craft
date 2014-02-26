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

use Craft\Reflect\Object;

trait Model
{

	/**
	 * Find many entities
	 * @param  array $where
	 * @param  mixed $orderBy
	 * @param  int $limit
	 * @param  int $step
	 * @return array
	 */
	public static function get(array $where = [], $orderBy = null, $limit = null, $step = null)
	{
		return Syn::get(static::model(), $where, $orderBy, $limit, $step);
	}


    /**
     * Count entities
     * @param $where
     * @return array
     */
    public static function has(array $where = [])
    {
        return Syn::count(static::model(), $where);
    }


    /**
     * Paginate a collection
     * @param $size
     * @param $page
     * @param $where
     * @param $sort
     * @return array
     */
    public static function paginate($size, $page, array $where = [], $sort = null)
    {
        return Syn::paginate(static::model(), $size, $page, $where, $sort);
    }


	/**
	 * Find one entities
	 * @param  int|array $where
	 * @return object|\stdClass|bool
	 */
	public static function one($where = null)
	{
		return Syn::one(static::model(), $where);
	}


	/**
	 * Save entity
	 * @param  object $entity
	 * @return bool
	 */
	public static function set($entity)
	{
		return Syn::save(static::model(), $entity);
	}


	/**
	 * Delete entity
	 * @param  object $entity
	 * @return bool
	 */
	public static function drop($entity)
	{
		return Syn::drop(static::model(), $entity);
	}


	/**
	 * Get model name
	 * @return  string
	 */
	protected static function model()
	{
        $class = Object::classname(get_called_class());
		return strtolower($class);
	}

}