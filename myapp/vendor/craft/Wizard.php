<?php

namespace Craft;

class Wizard
{

    /** @var array */
    protected $templates = [
        'auth.logic'            => 'auth/logic',
        'auth.view.login'       => 'auth/view.login',
        'auth.view.register'    => 'auth/view.register',
        'crud.logic'            => 'crud/logic',
        'crud.view.all'         => 'crud/view.all',
        'crud.view.one'         => 'crud/view.one',
        'crud.view.field'        => 'crud/view.field'
    ];

    /**
     * Define template files
     * @param array $templates
     */
    public function __construct(array $templates = [])
    {
        $this->templates = $templates + $this->templates;
    }


    /**
     * Create an auth controller
     * @param $model
     */
    public static function auth($model)
    {
        // todo
    }


    /**
     * Create a crud controller
     * @param $model
     */
    public static function crud($model)
    {
        // todo
    }

} 