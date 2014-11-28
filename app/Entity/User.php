<?php

namespace App\Entity;

/**
 * This is a database model.
 *
 * A model is an entity based on a database table.
 * Each property is a reference to a column.
 *
 * In order to create the table from the model,
 * use the `@var` metadata on each property and add the type
 * (int => integer, string => varchar, string text => text, string date => datetime)
 */
use Craft\Orm\Model;

/**
 * @entity user
 */
class User
{

    /**
     * This trait allow the model to use the Orm quick methods
     * such as : User::all(), User::save(), ...
     */
    use Model;

    /** @var int */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var string email */
    public $email;

    /** @var int */
    public $rank = 1;

} 