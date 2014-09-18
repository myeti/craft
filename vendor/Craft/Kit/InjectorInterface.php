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
     * Check if injector has definition
     * @param $class
     * @return bool
     */
    public function has($class);


    /**
     * Define params for class instance
     * @param $class
     * @param array $params
     * @return $this
     */
    public function define($class, array $params = []);


    /**
     * Set user factory
     * @param $class
     * @param callable $factory
     * @return $this
     */
    public function factory($class, callable $factory);


    /**
     * Define singleton
     * @param $class
     * @param array $params
     * @return $this
     */
    public function share($class, array $params = []);


    /**
     * Remove singleton
     * @param $class
     * @return $this
     */
    public function unshare($class);


    /**
     * Make class instance
     * @param $class
     * @param array $params
     * @return null
     */
    public function make($class, array $params = []);

} 