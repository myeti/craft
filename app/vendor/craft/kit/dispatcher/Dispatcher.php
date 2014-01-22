<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\dispatcher;

use craft\box\pattern\chain\Handler;
use craft\box\pattern\chain\HandlerChain;
use craft\box\pattern\chain\Material;
use craft\kit\event\Subject;

class Dispatcher extends HandlerChain
{

    use Subject;

    /**
     * Setup components
     * @param array $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers($handlers);
    }


    /**
     * Give material to all handlers whom are not in skip list
     * @param Material $material
     * @param array $skip
     * @return Material
     */
    public function run(Material $material, array $skip = [])
    {
        // start event
        $this->fire('start.dispatcher', ['input' => &$material]);

        // run chain
        try {
            $material = parent::run($material, $skip);
        }
        catch(DispatchException $e) {
            $this->fire($e->name, array_merge(['input' => &$material], $e->args));
        }

        // end process
        $this->fire('end.dispatcher', ['input' => &$material]);

        return $material;
    }


    /**
     * Give material to one handler
     * @param Material $material
     * @param Handler $handler
     * @return Material
     */
    public function give(Material $material, Handler $handler)
    {
        // before
        $this->fire('before.' . $handler->name(), ['input' => &$material]);

        // run handler
        $material = parent::give($material, $handler);

        // after
        $this->fire('after.' . $handler->name(), ['input' => &$material]);

        return $material;
    }

}