<?php

namespace Craft\Pattern\Chain;

interface Handler
{

    /**
     * Get handler name
     * @return string
     */
    public function name();


    /**
     * Take, proceed and return input
     * @param Material $material
     * @return Material
     */
    public function handle(Material $material);

} 