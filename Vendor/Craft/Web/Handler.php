<?php

namespace Craft\Web;

interface Handler
{

    /**
     * Handle context request
     * @param Context $context
     * @return mixed
     */
    public function handle(Context $context);

} 