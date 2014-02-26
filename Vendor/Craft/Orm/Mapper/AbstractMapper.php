<?php

namespace Craft\Orm\Mapper;

use Craft\Orm\Mapper;
use Craft\Reflect\Annotation;

abstract class AbstractMapper implements Mapper
{

    /** @var array */
    protected $models = [];


    /**
     * Map models
     * @param array $models
     * @return $this
     */
    public function map(array $models)
    {
        $this->models = $models;
        return $this;
    }


    /**
     * Get model namespace
     * @param $alias
     * @return string|bool
     */
    protected function model($alias)
    {
        return isset($this->models[$alias])
            ? $this->models[$alias]
            : false;
    }


    /**
     * Get schema of model
     * @param $alias
     * @return array
     */
    protected function schema($alias)
    {
        if($model = $this->model($alias)) {

            // get all properties
            $props = get_object_vars($model);

            // get env type
            $data = [];
            foreach($props as $prop) {
                $data[$prop] = Annotation::property($model, $prop, 'var');
            }

            return $data;
        }

        return false;
    }

} 