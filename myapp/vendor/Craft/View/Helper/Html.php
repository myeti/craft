<?php

namespace Craft\View\Helper;

use Craft\View\Helper;

class Html implements Helper
{

    /**
     * Register helper functions
     * @return mixed
     */
    public function register()
    {
        return [
            'meta'  => [$this, 'meta'],
            'e'     => [$this, 'e']
        ];
    }


    /**
     * Basic meta
     * @return string
     */
    public function meta()
    {
        $meta = [
            '<meta charset="UTF-8">',
            '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1" />'
        ];
        return implode("\n", $meta);
    }


    /**
     * Escape string
     * @param $string
     * @return string
     */
    public function e($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

}