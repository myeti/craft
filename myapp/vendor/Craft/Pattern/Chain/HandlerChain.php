<?php

namespace Craft\Pattern\Chain;

class HandlerChain
{

    /** @var Handler[] */
    protected $handlers = [];


    /**
     * Add handler
     * @param Handler $handler
     * @return $this
     */
    public function handler(Handler $handler)
    {
        $this->handlers[] = $handler;

        return $this;
    }


    /**
     * Add many handlers
     * @param array $handlers
     * @return $this
     */
    public function handlers(array $handlers)
    {
        foreach($handlers as $handler) {
            $this->handler($handler);
        }

        return $this;
    }


    /**
     * Give material to all handlers whom are not in skip list
     * @param Material $material
     * @param array $skip
     * @return Material
     */
    public function run(Material $material, array $skip = [])
    {
        // start chaining
        foreach($this->handlers as $handler) {

            // skip this one
            if(in_array($handler->name(), $skip)) {
                continue;
            }

            // give to handler
            $material = $this->give($material, $handler);

        }

        return $material;
    }


    /**
     * Give material to one handler
     * @param Material $material
     * @param Handler $handler
     * @return Material
     */
    protected function give(Material $material, Handler $handler)
    {
        return $handler->handle($material);
    }

}