<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

use Craft\App\Roadmap;
use Craft\Pattern\Chain\Handler as ChainHandler;
use Craft\Pattern\Chain\Material;

abstract class Handler implements ChainHandler
{

    /**
     * Handle only context object
     * @param Material $material
     * @return Material|void
     */
    public function handle(Material $material)
    {
        if($material instanceof Roadmap) {
            $material = $this->handleRoadmap($material);
        }

        return $material;
    }

    /**
     * Handle web context
     * @param Roadmap $roadmap
     * @return Roadmap
     */
    abstract protected function handleRoadmap(Roadmap $roadmap);

}