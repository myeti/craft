<?php

namespace Craft\Box;

use Craft\Data\Map;
use Craft\Debug\Error\FileNotFound;

class Config
{

    /** @var string */
    protected static $dir;

    /** @var Map[] */
    protected static $configs = [];


    /**
     * Set config directory
     * @param string $dir
     */
    public static function in($dir)
    {
        static::$dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }


    /**
     * Get config
     * @param string $path
     * @throws FileNotFound
     * @return mixed|mixed[]
     */
    public static function get(...$args)
    {
        // multiple get
        if(count($args) > 1){

            $values = [];
            foreach($args as $arg) {
                $values[] = static::get($arg);
            }

            return $values;
        }

        // parse path
        $exp = explode('.', $path);
        $file = array_shift($exp);
        $key = implode('.', $exp);

        // read config
        if(!isset(static::$configs[$file])) {

            // get full path
            $path = static::$dir . $file . '.php';

            // no config file
            if(!file_exists($path)) {
                throw new FileNotFound('Config file "' . $path . '" not found.');
            }

            // add config
            static::$configs[$file] = new Map(require $path);
        }

        // get config
        return static::$configs[$file]->get($key);
    }

} 