<?php

namespace my\models;

/**
 * Hey ! This is your first model.
 *
 * A model is entity based on a database table.
 * Each property is a reference to a column.
 *
 * In order to create the table from the model,
 * use the `@var` metadata on each property and add the type
 * (int => integer, string => varchar, string text => text, string date => datetime)
 */
use craft\orm\Model;

class User
{

    /**
     * This trait allow the model to use the Syn methods
     * such as : User::find(), User::save(), ...
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