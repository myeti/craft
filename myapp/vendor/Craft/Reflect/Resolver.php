<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Reflect;

use Craft\Reflect\Resolver\ClassMethodResolver;
use Craft\Reflect\Resolver\FunctionResolver;
use Craft\Reflect\Resolver\StaticMethodResolver;
use Craft\Reflect\Resolver\TypeResolver;

class Resolver
{

    /** @var TypeResolver[] */
    protected $resolvers = [];


    /**
     * Set default resolvers
     * @param array $resolvers
     */
    public function __construct(array $resolvers = [])
    {
        $this->resolvers = $resolvers + [
            'function'      => new FunctionResolver(),
            'static_method' => new StaticMethodResolver(),
            'class_method'  => new ClassMethodResolver()
        ];
    }


    /**
     * Check if input is callable action
     * @param $input
     * @return bool
     */
    public function isAction($input)
    {
        foreach($this->resolvers as $type => $resolver) {
            if($resolver->is($input)) {
                return $type;
            }
        }

        return false;
    }


    /**
     * Resolve input to action
     * @param $input
     * @return bool|Action
     */
    public function resolve($input)
    {
        foreach($this->resolvers as $type => $resolver) {
            if($resolver->is($input)) {
                $action = $resolver->resolve($input);
                $action->type = $type;
                return $action;
            }
        }

        return false;
    }


    /**
     * Quick alias
     * @param $input
     * @return bool|Action
     */
    public static function apply($input)
    {
        $self = new self();
        return $self->resolve($input);
    }

} 