<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Console;

abstract class Command
{

    /** @var string */
    public $description = 'no description';

    /** @var Command\Argument[] */
    protected $args = [];

    /** @var Command\Option[] */
    protected $options = [];


    /**
     * Add argument
     * @param string $name
     * @param int $type
     * @return $this
     */
    protected function arg($name, $type = Command\Argument::REQUIRED)
    {
        $this->args[] = new Command\Argument($name, $type);
        return $this;
    }


    /**
     * Get all args
     * @return Command\Argument[]
     */
    public function args()
    {
        return $this->args;
    }


    /**
     * Add option
     * @param string $name
     * @param int $type
     * @return $this
     */
    protected function opt($name, $type = Command\Option::OPTIONAL)
    {
        $this->options[] = new Command\Option($name, $type);
        return $this;
    }


    /**
     * Get all options
     * @return Command\Option[]
     */
    public function options()
    {
        return $this->options;
    }


    /**
     * Execute command
     * @param object $args
     * @param object $options
     */
    abstract protected function run($args, $options);

}