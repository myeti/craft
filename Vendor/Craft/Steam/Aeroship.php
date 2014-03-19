<?php

namespace Craft\Steam;

use Craft\App\Kernel;
use Craft\App\Plugin;

class Aeroship
{

    /** @var Kernel */
    protected $engine;

    /**
     * Setup airship
     * @param Kernel $kernel
     */
    public function __construct(Kernel $engine = null)
    {
        $this->engine = $engine ?: new Kernel;
    }


    /**
     * Hire gear
     * @param Plugin $gear
     * @return $this
     */
    public function engage(Plugin $gear)
    {
        return $this->engine->plug($gear);
    }


    /**
     * Let's go captain !
     */
    public function fly()
    {
        return $this->engine->handle();
    }

} 