<?php

namespace Craft\Debug;

abstract class Bada
{

    /**
     * Dump and die
     * @param $var
     */
    public static function boom(...$vars)
    {
        ob_clean();
        echo '<h1>BOOM !</h1>';
        if($vars) {
            var_dump(...$vars);
        }
        exit;
    }


    /**
     * Dump step
     * @param $message
     */
    public static function step($message = 'step')
    {
        static $steps;
        if(!$steps) {
            $steps = [];
        }
        if(!isset($steps[$message])) {
            $steps[$message] = 1;
        }

        var_dump($message .':' . $steps[$message]++);
    }

} 