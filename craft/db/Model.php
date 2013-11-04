<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 *
 * @author Aymeric Assier <aymeric.assier@gmail.com>
 * @date 2013-09-12
 * @version 0.1
 */
namespace craft\db;

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
	public static function find(array $where = [], $orderBy = null, $limit = null, $step = null)
	{
		return Syn::find(static::model(), $where, $orderBy, $limit, $step);
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
	public static function save(&$entity)
	{
		return Syn::save(static::model(), $entity);
	}

	/**
	 * Delete entity
	 * @param  object $entity
	 * @return bool
	 */
	public static function wipe($entity)
	{
		return Syn::wipe(static::model(), $entity);
	}

	/**
	 * Get model name
	 * @return  string
	 */
	protected static model()
	{
		$exp = explode('\\', get_called_class());
		return strtolower(end($exp));
	}

}