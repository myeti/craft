<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Data;

use Craft\Box\Meta\Resolver;

class Validator
{

    /** @var array */
    protected $rules = [];


    /**
     * Check if element exists
     * @param string $rule
     * @return bool
     */
    public function has($rule)
    {
        return isset($this->rules[$rule]);
    }


    /**
     * Set element by key with value
     * @param string $rule
     * @param callable $validator
     * @return bool
     */
    public function set($rule, $validator)
    {
        $this->rules[$rule] = $validator;
    }


    /**
     * Drop element by key
     * @param $rule
     * @return bool
     */
    public function drop($rule)
    {
        unset($this->rules[$rule]);
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
        foreach($this->rules as $rule) {

            // apply rule
            $data = Resolver::call($rule, [$input], true);

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