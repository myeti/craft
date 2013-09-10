<?php

namespace craft;

class Builder
{

    /** @var array */
    protected $_config = [
        'base' => null
    ];

    /**
     * Setup with config
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->_config = $config + $this->_config;
    }

    /**
     * Turn target into \Closure and get metadata
     * @param $target
     * @throws \InvalidArgumentException
     * @return bool|\stdClass
     */
    public function resolve($target)
    {
        // function
        if($target instanceof \Closure or (is_string($target) and function_exists($target)))
        {
            // apply reflection
            $ref = new \ReflectionFunction($target);
            $closure = $target instanceof \Closure ? $target : $ref->getClosure();
        }
        // method in array
        elseif(($is_string = is_string($target) and strpos('::', $target)) or (is_array($target) and count($target) === 2)){

            // resolve class::method
            if($is_string){
                $target = explode('::', $target);
            }

            // base namespace
            $target[0] = $this->_config['base'] . $target[0];

            // apply reflection
            $ref = new \ReflectionMethod($target[0], $target[1]);
            $closure = $ref->isStatic() ? $ref->getClosure() : $ref->getClosure(new $target[0]());
        }
        else {
            throw new \InvalidArgumentException;
        }

        // get metadata
        preg_match_all('/@([a-zA-Z0-9]+) ([a-zA-Z0-9._\-\/ ]+)/', $ref->getDocComment(), $out, PREG_SET_ORDER);
        $metadata = [];
        foreach($out as $match)
            $metadata[$match[1]] = $match[2];

        // make statement
        $stm = new \stdClass();
        $stm->action = $closure;
        $stm->metadata = $metadata;

        return $stm;
    }

    /**
     * Call action
     * @param callable $action
     * @param array $args
     * @return mixed
     */
    public function call(callable $action, array $args = [])
    {
        return call_user_func_array($action, $args);
    }

}