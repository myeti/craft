<?php

namespace Craft\Contract;

interface Invokable
{

    /**
     * Force invoke method
     * @return mixed
     */
    public function __invoke();

} 