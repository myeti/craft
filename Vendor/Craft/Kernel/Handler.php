<?php

namespace Craft\Kernel;

interface Handler
{

    /**
     * Handle context request
     * @param Context $context
     * @return mixed
     */
    public function handle(Context $context);

} 