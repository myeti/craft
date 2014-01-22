<?php

namespace craft\box\pattern\chain;

class HandlerChain
{

    /** @var array */
    protected $_handlers = [];


    /**
     * Add handler
     * @param Handler $handler
     * @return $this
     */
    public function handler(Handler $handler)
    {
        $this->_handlers[] = $handler;

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
        // make handler list
        $handlers = array_diff_key($this->_handlers, array_flip($skip));

        // start chaining
        foreach($handlers as $handler) {
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