<?php

namespace Craft\Trace\Panel;

class Stack
{

    /** @var string */
    public $name;

    /** @var array  */
    protected $data = [];


    /**
     * Add data line
     * @param string $content
     * @param string $aside
     * @return $this
     */
    public function add($content, $aside = null)
    {
        $this->data[] = (object)[
            'content' => $content,
            'aside' => $aside,
            'time' => time()
        ];

        return $this;
    }


    /**
     * Get all data
     * @return array
     */
    public function data()
    {
        return $this->data;
    }

} 