<?php

namespace craft\kit\wizard;

abstract class Wizard
{

    /** @var array */
    protected static $_files = [
        'auth.logic'            => 'auth/logic',
        'auth.view.login'       => 'auth/view.login',
        'auth.view.register'    => 'auth/view.register',
        'crud.logic'            => 'crud/logic',
        'crud.view.all'         => 'crud/view.all',
        'crud.view.one'         => 'crud/view.one',
        'crud.view.form'        => 'crud/view.form'
    ];


    /**
     * Set template file
     * @param $template
     * @param $file
     */
    public static function file($template, $file)
    {
        static::$_files[$template] = $file;
    }


    /**
     * Create an auth controller
     * @param $model
     */
    public static function auth($model)
    {

    }


    /**
     * Create a crud controller
     * @param $model
     */
    public static function crud($model)
    {

    }

} 