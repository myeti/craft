<?php

namespace Forge;

use Craft\App\Kernel as NativeKernel;

class Kernel extends NativeKernel
{

    /**
     * 404 Not found
     * @param string $to
     */
    public function oops($to)
    {
        $this->on('error.404', function() use($to) {
            $this->to($to);
        });
    }


    /**
     * 403 Forbidden
     * @param string $to
     */
    public function nope($to)
    {
        $this->on('error.403', function() use($to) {
            $this->to($to);
        });
    }

}