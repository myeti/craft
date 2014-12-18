<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Kit;

interface InjectorInterface
{

    /**
     * Has instance definition
     * @param string $class
     * @return bool
     */
    public function has($class);


    /**
     * Store instance
     * @param string $class
     * @param object $instance
     */
    public function store($class, &$instance);


    /**
     * Set user factory
     * @param string $class
     * @param callable $factory
     * @param bool $singleton
     */
    public function define($class, callable $factory, $singleton = false);


    /**
     * Make class instance
     * @param string $class
     * @param array $params
     * @return object
     */
    public function make($class, array $params = []);

} 