<?php

namespace Craft\View;

interface EngineInterface
{

    /**
     * Render data with resource
     * @param $something
     * @return mixed
     */
    public function render($something);

} 