<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Cli;

abstract class Command
{

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    protected $params;


    /**
     * Setup command
     * @param string $name
     * @param string $params
     * @param string $description
     */
    public function __construct($name, $params, $description)
    {
        $this->name = $name;
        $this->description = $description;
        $this->params = $params;
    }


    /**
     * Run command
     * @param array $args
     * @return string
     */
    public function run()
    {
        // parse args
        $skip = [];
        foreach($args as $k => $argument) {

            // skip argument
            if(in_array($k, $skip)) {
                continue;
            }

            // flag
            if(substr($argument, 0, 2) == '--') {

                // parse flag
                $flag = substr($argument, 2);

                // error
                if(!isset($flags[$flag])) {
                    return 'Unknown flag "' . $flag . '".';
                }

                $flags[$flag] = true;
            }
            // parameter
            elseif(substr($argument, 0, 1) == '-') {

                // parse parameter
                $parameter = substr($argument, 1);

                // error
                if(!isset($parameters[$parameter])) {
                    return 'Unknown parameter "' . $parameter . '".';
                }
                elseif(!isset($args[$k + 1])) {
                    return 'Parameter "' . $parameter . '" must have a value.';
                }

                // get value
                $value = $args[$k + 1];
                $skip[] = $k + 1;

                $parameters[$parameter] = $value;

            }
            // argument
            else {

                // parse argument
                list($argument, $value) = explode(' ', $argument);

                // error
                if(!isset($arguments[$argument])) {
                    return 'Unknown argument "' . $argument . '".';
                }
                elseif(!$value) {
                    return 'Argument "' . $argument . '" must have a value.';
                }

                $arguments[$argument] = $value;

            }

            // argument missing ?
            foreach($arguments as $name => $val) {
                if(!$val) {
                    return 'Argument "' . $name . '" is missing.';
                }
            }

            // execute command
            $this->execute($arguments, $parameters, $flags);
        }
    }


    /**
     * Execute command
     * @param array $arguments
     * @param array $parameters
     * @param array $flags
     * @return mixed
     */
    abstract public function execute(array $arguments, array $parameters, array $flags);

}