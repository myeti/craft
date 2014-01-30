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

use Craft\Pattern\Chain\Handler;
use Craft\Pattern\Chain\HandlerChain;
use Craft\Pattern\Chain\Material;
use Craft\Pattern\Event\Subject;
use Craft\Box\Error\SomethingIsWrongException;

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
        $this->fire('start.dispatcher', [&$material]);

        // run chain
        try {
            $material = parent::run($material, $skip);
        }
        catch(SomethingIsWrongException $e) {
            $this->fire($e->getCode(), [&$material]);
        }

        // end process
        $this->fire('end.dispatcher', [&$material]);

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
        $this->fire('before.' . $handler->name(), [&$material]);

        // run handler
        $material = parent::give($material, $handler);

        // after
        $this->fire('after.' . $handler->name(), [&$material]);

        return $material;
    }

}