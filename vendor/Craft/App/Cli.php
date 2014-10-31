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

use Craft\Router;
use Craft\View;
use Craft\Box\Mog;

/**
 * Ready to use app
 */
class Cli extends Kernel
{

    /**
     * Ready-to-use app
     * @param Router\Seeker $router
     */
    public function __construct(Router\Seeker $router)
    {
        // init built-in services
        $this->plug(new Service\Routing($router));
        $this->plug(new Service\CliWrapping($router));
    }


    /**
     * Write message
     * @param string $message
     * @return Cli\Dialog
     */
    public static function say(...$message)
    {
        return static::dialog()->say(...$message);
    }


    /**
     * Write new line
     * @return Cli\Dialog
     */
    public static function ln()
    {
        return static::dialog()->ln();
    }


    /**
     * Get user input
     * @return string
     */
    public static function read()
    {
        return static::dialog()->read();
    }


    /**
     * Ask question and get user answer
     * @param $message
     * @return string
     */
    public static function ask(...$message)
    {
        return static::dialog()->ask(...$message);
    }


    /**
     * Dialog instance
     * @return Cli\Dialog
     */
    protected static function dialog()
    {
        static $dialog;
        if(!$dialog) {
            $dialog = new Cli\Dialog;
        }

        return $dialog;
    }

}