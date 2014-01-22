<?php

namespace craft\kit\app;

use craft\box\pattern\chain\Handler;
use craft\box\pattern\chain\Material;

abstract class ContextHandler implements Handler
{

    /**
     * Handle only context object
     * @param Material $material
     * @return Material|void
     */
    public function handle(Material $material)
    {
        if($material instanceof Context) {
            $material = $this->handleContext($material);
        }

        return $material;
    }

    /**
     * Handle app context
     * @param Context $context
     * @return Context
     */
    abstract protected function handleContext(Context $context);

}