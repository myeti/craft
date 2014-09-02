<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

use Craft\Event;
use Craft\Map\RouterInterface;
use Craft\View\EngineInterface;

/**
 * Ready to use app
 */
class Bundle extends Kernel
{

    const DEV = 1;
    const PROD = 2;

    /** @var int */
    protected static $mode = self::DEV;


    /**
     * Ready-to-use app
     * @param RouterInterface $router
     * @param EngineInterface $engine
     * @param int $mode
     */
    public function __construct(RouterInterface $router, EngineInterface $engine, $mode = self::DEV)
    {
        // init kernel
        parent::__construct();

        // set mode
        static::$mode = $mode;

        // init built-in services
        $this->plug(new Service\RouterService($router));
        $this->plug(new Service\ResolverService);
        $this->plug(new Service\AuthService);
        $this->plug(new Service\RenderService($engine));

        // error handling : dev mode only
        if(static::dev()) {
            $this->plug(new Service\WhoopsService);
        }
    }


    /**
     * Is dev mode
     * @return bool
     */
    public static function dev()
    {
        return static::$mode == self::DEV;
    }


    /**
     * Is prod mode
     * @return bool
     */
    public static function prod()
    {
        return static::$mode == self::PROD;
    }

}