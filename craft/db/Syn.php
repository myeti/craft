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

abstract class Syn
{

	/** @var \PDO */
	protected $_pdo;

	/** @var string */
	protected $_prefix;

	/** @var array */
	protected $_models = [];

	/**
	 * Register models@
	 * @param  array  $models
	 */
	public static function map(array $models)
	{
		static::$_models = $models;
	}

    /**
     * Get or set PDO instance
     * @param \PDO|null $pdo
     * @throws \RuntimeException
     * @return \PDO
     */
	public static function pdo(\PDO $pdo = null)
	{
		// set
		if($pdo){
			static::$_pdo = $pdo;
		}
		elseif(!static::$_pdo){
			throw new \RuntimeException('PDO is not defined in Syn, please setup with Syn::mysql');
		}

		return static::$_pdo;
	}

	/**
	 * Helper : Connect PDO to MySQL
	 * @param  array  $config
	 */
	public static function mysql(array $config)
	{
		$config = [
			'host' => '127.0.0.1',
			'user' => 'root',
			'pass' => '',
			'name' => 'mydb',
			'prefix' => ''
		] + $config;

		static::$_prefix = $config['prefix'];

		static::$_pdo = new \PDO(
        	'mysql:host=' . $config['host'] . ';dbname=' . $config['name'],
        	$config['user'],
        	$config['pass']
        );
	}

	/**
	 * Helper : Connect PDO to SQLite
	 * @param string $filename
	 * @param string $prefix
	 */
	public static function sqlite($filename, $prefix = null)
	{
		static::$_prefix = $prefix;
		static::$_pdo = new \PDO('sqlite:' . $filename);
	}

	/**
	 * Execute a csutom sql request
	 * @param  string $sql
	 * @param  string $cast
     * @return array
     */
	public static function query($sql, $cast = null)
	{
		// execute
        $result = $cast
            ? static::pdo()->query($sql, \PDO::FETCH_CLASS, static::$_models[$cast])
            : static::pdo()->query($sql, \PDO::FETCH_OBJ);

        return $result->fetchAll();
	}

    /**
     * Find a collection
     * @param  string $table
     * @param  array $where
     * @param null $orderBy
     * @param null $limit
     * @param null $step
     * @return array
     */
	public static function find($table, array $where = [], $orderBy = null, $limit = null, $step = null)
	{
		// prepare sql
		$sql = 'SELECT * FROM `' . static::$_prefix . $table . '`';

		// where clause
        if($where)
        {
            $sql .= ' WHERE 1';
            foreach($where as $field => $condition)
                $sql .= ' AND `' . $field . '` = "' . $condition . '"';
        }

        // order by clause
        if($orderBy) {

            $sql .= ' ORDER BY';

            if(is_array($orderBy)) {
                foreach($orderBy as $field => $dir)
                    $sql .= ' `' . $field . '` ' . strtoupper($dir);
            }
            else {
                $sql .= ' `' . (string)$orderBy . '`';
            }

        }

        // limit
        if($limit or $limit === 0){

    		$sql .= ' LIMIT ' . $limit;

    		if($step){
    			$sql .= ', ' . $step;
    		}

        }

        // execute
        $sth = static::pdo()->prepare($sql);
        $result = $sth->execute();

        // not a select query
        if(!$sth->columnCount()){
        	return $result;
        }

        // result set
        $rows = !empty(static::$_models[$table])
            ? $sth->fetchAll(\PDO::FETCH_CLASS, static::$_models[$table])
            : $sth->fetchAll(\PDO::FETCH_OBJ);

        return $rows;
	}

	/**
	 * Find a specific entity
	 * @param  string $table
	 * @param  mixed $where
	 * @return object|\stdClass
	 */
	public static function one($table, $where = null)
	{
		// id or conditions ?
		if($where and is_int($where)){
			$where = ['id' => $where];
		}

		// execute
		$results = static::find($table, $where, null, 1);

		return count($results) ? $results[0] : false;
	}

	/**
	 * Persist entity
	 * @param string $table
	 * @param object $entity
	 * @return bool
	 */
	public static function save($table, &$entity)
	{
		// cast to object
        $entity = (object)$entity;

        // extract data
        $data = get_object_vars($entity);

        // insert
        if(empty($data['id']))
        {
            // exclude id
            unset($data['id']);

            // prepare sql
            $sql = 'INSERT INTO `' . static::$_prefix . $table . '`';

            // fields
            $sql .= ' (`' . implode('`, `', array_keys($data)) . '`)';

            // values
            $sql .= ' VALUES ("' . implode('", "', $data) . '")';
        }
        // update
        else
        {
            // exclude id
            $id = $data['id'];
            unset($data['id']);

            // prepare sql
            $sql = 'UPDATE `' . static::$_prefix . $table . '` SET ';

            // prepare set
            $set = [];
            foreach($data as $field => $value)
                $set[] = '`' . $field . '` = "' . $value . '"';

            // add values
            $sql .= implode(', ', $set);

            // where clause
            $sql .= ' WHERE `id` = ' . $id;
        }

        // execute
        $result = static::pdo()->exec($sql);

        // re-hydrate object
        if($result) {

            // has id
            $newId = $entity->id ?: static::pdo()->lastInsertId();
            if($newId)
                $entity = static::one($table, $newId);

        }

        return $result;
	}

	/**
	 * Delete entity
	 * @param  string $table
	 * @param  mixed $entity
     * @return bool|int
     */
	public static function wipe($table, $entity)
	{
		// resolve id
		$id = is_object($entity) ? $entity->id : $entity;

		// id needed
		if(!$id){
			return false;
		}

        // prepare sql
        $sql = 'DELETE FROM `' . static::prefix() . $table . '`';

        // where clause
        $sql .= ' WHERE `id` = ' . $id;

        // execute
        $result = static::pdo()->exec($sql);

        return $result;
	}

	/**
     * Sync model with database
     * @return \PDOStatement
     */
    public static function merge()
    {
        // init schema
        $tables = [];

        /**
         * get schema for each models
         * @var $collection Collection
         */
        foreach(static::$_models as $name => $model) {

            // init infos
            $table = [];

            // get ref
            $ref = new \ReflectionClass($model);

            // get parameters name and type
            foreach($ref->getProperties() as $property) {

                // get phpdoc
                $doc = $property->getDocComment();

                // get type
                if($property->isPublic() and preg_match('/@var ([a-z ]+)/', $doc, $matches))
                    $table[$property->getName()] = trim($matches[1]);
            }

            // add table to schema
            $tables[$name] = $table;

        }

        // create tables
        $query = [];

        foreach($tables as $name => $details) {

            // create table if not exists
            $subquery = 'create table if not exists `' . static::$_prefix . $name . '` (';

            foreach($details as $field => $prop) {
                $subquery .= '`' . $field . '` ';

                // define type
                switch($prop) {
                    case 'int' : $type = 'int'; break;
                    case 'string' : $type = 'varchar(255)'; break;
                    case 'string text' : $type = 'text'; break;
                    case 'string date' : $type = 'datetime';  break;
                    default: $type = 'varchar(255)'; break;
                }

                $subquery .= $type . ' ';

                // id ?
                $subquery .= ($field == 'id') ? ' not null auto_increment,' : ' default null,';

            }

            $subquery .= 'primary key (`id`));';

            // add to general query
            $query[] = $subquery;

        }

        // alter fields
        foreach($tables as $name => $details) {

            // alter table
            $subquery = 'alter table `' . static::$_prefix . $name . '` ';

            foreach($details as $field => $prop) {
                $subquery .= 'modify `' . $field . '` ';

                // define type
                switch($prop) {
                    case 'int' : $type = 'int'; break;
                    case 'string' : $type = 'varchar(255)'; break;
                    case 'string text' : $type = 'text'; break;
                    case 'string date' : $type = 'datetime';  break;
                    default: $type = 'varchar(255)'; break;
                }

                $subquery .= $type . ' ';

                // id ?
                $subquery .= ($field == 'id') ? ' not null auto_increment,' : ' default null,';

            }

            // add to general query
            $query[] = $subquery;
        }

        return static::query(implode("\n", $query));
    }

    /**
     * Create a backup of the database in sql
     * @param $filename string
     * @return string
     */
    public function backup($filename)
    {

        // get table list
        $tables = [];
        foreach(static::pdo()->query('show tables')->fetchAll() as $row)
            $tables[] = $row[0];

        // init backup sql
        $backup = '';

        // table structure
        foreach($tables as $table) {

            // create table
            $backup .= 'DROP TABLE IF EXISTS `' . $table . '`;' . "\n";
            $backup .= 'CREATE TABLE `' . $table . '` (' . "\n";

            // get fields
            $fields = static::pdo()->query('describe `' . $table . '`')->fetchAll();

            // build lines
            $lines = [];
            foreach($fields as $field) {

                // new line
                $line = "\t";

                // name and type
                $line .= '`' . $field['Field'] . '` ' . $field['Type'];

                // default value
                if($field['Default'])
                    $line .= ' DEFAULT ' . $field['Default'];

                // null value
                if($field['Null'] == 'NO')
                    $line .= ' NOT NULL';

                // extra value
                if($field['Extra'])
                    $line .= ' ' . $field['Extra'];

                // primary key
                if($field['Key'] == 'PRI')
                    $line .= ' PRIMARY KEY';

                // push line
                $lines[] = $line;
            }

            $backup .= implode($lines, ",\n") . "\n);\n\n";
        }

        // data backup
        foreach($tables as $table) {

            // find data
            $lines = [];
            foreach($this->__get($table)->find() as $row) {

                // new line
                $line = 'INSERT INTO `' . $table . '` (`';

                // add fields
                $fields = array_keys(get_object_vars($row));
                $line .= implode($fields, '`, `');

                // add values
                $line .= '`) VALUES (';
                $values = get_object_vars($row);

                // escape input
                foreach($values as $k => $v){
                	$values[$k] = static::pdo()->quote($v);
                }
                $line .= implode($values, ', ');

                // close and push line
                $line .= ');';
                $lines[] = $line;

            }

            $backup .= implode($lines, "\n") . "\n\n";

        }

        // write backup in file
        return file_put_contents($filename, $backup);
    }

}