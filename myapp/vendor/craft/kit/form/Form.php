<?php

namespace craft\kit\form;

use craft\box\data\Repository;
use craft\box\data\SilentArray;
use craft\box\meta\Annotation;
use craft\kit\view\Template;

class Form extends SilentArray
{

    /** @var array */
    public $config = [];

    /** @var string */
    public $url;

    /** @var string */
    public $method;


    /**
     * Load entity
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // default config
        $defaults = [
            'form' => [
                'url'      => '#',
                'method'   => 'post',
                'template' => __DIR__ . '/templates/form',
                'entity'   => null,
                'types'    => [
                    'hidden'           => 'craft\kit\form\fields\HiddenField',
                    'int'              => 'craft\kit\form\fields\StringField',
                    'string'           => 'craft\kit\form\fields\StringField',
                    'string text'      => 'craft\kit\form\fields\TextField',
                    'string email'     => 'craft\kit\form\fields\EmailField',
                    'string date'      => 'craft\kit\form\fields\StringField',
                    'string datetime'  => 'craft\kit\form\fields\StringField',
                    'default'          => 'craft\kit\form\fields\StringField',
                ]
            ]
        ];

        // create repo
        $this->config = new Repository($defaults);

        // parse entity
        if(isset($config['form.entity']) and is_object($config['form.entity'])) {
            foreach($config['form.entity'] as $prop => $value) {
                $this->config->set($prop, [
                    'value' => $value,
                    'type' => Annotation::property($config['form.entity'], $prop, 'var'),
                ]);
            }
        }

        // override
        $this->config->override($config);

        // hydrate with fields
        $fields = clone $this->config;
        unset($fields['form']);
        foreach($fields as $name => $info) {

            // get field type + fallback
            $fieldType = $this->config->get('form.types.' . trim($info['type']),
                $this->config->get('form.types.default')
            );

            // create field
            $field = new $fieldType($name, $info);
            $this[$name] = $field;

        }

        // set params
        $this->url = $this->config->get('form.url');
        $this->method = $this->config->get('form.method');

    }


    /**
     * Return template to use
     * @return string
     */
    public function html()
    {
        return Template::forge($this->config->get('form.template'), ['form' => $this]);
    }

}