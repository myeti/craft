<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Router;

use Craft\Kit\Metadata;

class Api extends Urls
{


    /**
     * Setup matcher
     * @param array $classes
     */
    public function __construct(array $classes = [])
    {
        foreach($classes as $class) {
            $this->map($class);
        }
    }


    /**
     * Make route from class methods
     * @param string $class
     * @return $this
     */
    public function map($class)
    {
        // get prefix
        if($prefix = Metadata::object($class, 'url')) {
            $prefix = '/' . trim($prefix, '/');
        }

        // scan & parse
        foreach(get_class_methods($class) as $method) {
            if($url = Metadata::method($class, $method, 'url')) {
                $url = $prefix . '/' . ltrim($url, '/');
                $route = new Route($url, $class . '::' . $method);
                $this->add($route);
            }
        }

        return $this;
    }

}