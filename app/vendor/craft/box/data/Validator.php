<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\data;

use craft\box\meta\Action;

class Validator
{

    /** @var array */
    protected $_rules = [];


    /**
     * Check if element exists
     * @param string $rule
     * @return bool
     */
    public function has($rule)
    {
        return isset($this->_rules[$rule]);
    }


    /**
     * Set element by key with value
     * @param string $rule
     * @param callable $validator
     * @return bool
     */
    public function set($rule, $validator)
    {
        $this->_rules[$rule] = $validator;
    }


    /**
     * Drop element by key
     * @param $rule
     * @return bool
     */
    public function drop($rule)
    {
        unset($this->_rules[$rule]);
    }


    /**
     * Valid input with rules
     * @param $input
     * @return \stdClass
     */
    public function valid($input)
    {
        // init
        $errors = [];
        $valid = true;

        // apply all rules
        foreach($this->_rules as $rule) {

            // apply rule
            $data = Action::call($rule, [$input], true);

            // error
            if($data !== true) {

                // force array
                if(!is_array($data)) {
                    $data = [$data];
                }

                // push errors
                $valid = false;
                array_merge($errors, $data);

            }

        }

        // report
        $report = new \stdClass();
        $report->valid = $valid;
        $report->errors = $errors;

        return $report;
    }

}