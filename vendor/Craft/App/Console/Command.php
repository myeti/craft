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
     * Execute command
     * @param object $args
     * @param object $options
     * @return mixed
     */
    abstract protected function run($args, $options);


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
     * Check if target command
     */
    public function valid(array $argv)
    {
        // init
        $args = $options = [];

        // parse args
        foreach($this->args as &$arg) {

            // init
            $args[$arg->name] = false;

            // is valid argument ?
            $valid = (substr(current($argv), 0, 1) !== '-');

            // is required & not valid argument
            if($arg->isRequired() and (!$argv or !$valid)) {
                Console::say('missing argument "', $arg->name, '"')->ln();
                return false;
            }
            // valid argument
            elseif($valid) {
                $args[$arg->name] = array_shift($argv);
            }
            // not valid argument
            else {
                break;
            }

        }

        // prepare argv for options parsing
        $query = implode('&', $argv);
        foreach($this->options as $opt) {
            if($opt->isMultiple()) {
                $query = str_replace('-' . $opt->name, '-' . $opt->name . '[]', $query);
            }
        }
        parse_str($query, $argv);

        // parse options
        foreach($this->options as &$opt) {

            // init
            $options[$opt->name] = $opt->isMultiple() ? array() : false;
            $key = (strlen($opt->name) === 1 ? '-' : '--') . $opt->name;

            // option exists
            if(isset($argv[$key])) {

                // clean
                if($argv[$key] == '') {
                    $argv[$key] = null;
                }

                // error : must not have a value
                if($opt->isEmpty() and $argv[$key] != null) {
                    Console::say('option "', $opt->name, '" accepts no value')->ln();
                    return false;
                }
                // error : must have a value
                elseif($opt->isRequired() and $argv[$key] == null) {
                    Console::say('option "', $opt->name, '" must have a value')->ln();
                    return false;
                }
                // error : must have one or many value
                elseif($opt->isMultiple() and empty($argv[$key])) {
                    Console::say('option "', $opt->name, '" must have at least one value')->ln();
                    return false;
                }

                // valid value, set option
                $options[$opt->name] = ($argv[$key] == null) ? true : $argv[$key];

                unset($argv[$key]);
            }

        }

        // unknown params
        if($argv) {
            $name = is_int(key($argv)) ? current($argv) : key($argv);
            Console::say('unknown parameter "', $name, '"')->ln();
            return false;
        }

        // good, execute command
        return $this->run((object)$args, (object)$options);
    }

}