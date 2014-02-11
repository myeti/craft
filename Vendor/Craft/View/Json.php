<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View;

class Json implements Viewable
{

    /** @var \Closure */
    protected $wrapper;


    /**
     * Setup json with wrapper
     * @param callable $wrapper
     */
    public function __construct(\Closure $wrapper = null)
    {
        $this->wrapper = $wrapper;
    }


    /**
     * Render view
     * @param null|\Closure $wrapper
     * @param array $data
     * @return string
     */
    public function render($wrapper = null, array $data = [])
    {
        $wrapper = $wrapper ?: $this->wrapper;
        if($wrapper) {
            $data = call_user_func($this->wrapper, $data);
        }

        return json_encode($data);
    }

}