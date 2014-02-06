<?php

namespace Craft\View;

interface Viewable
{

    /**
     * Render view
     * @param mixed $something
     * @return string
     */
    public function render($something);

} 