<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\core;

use craft\meta\Events;

class Dispatcher
{

    use Events;

    /** @var array */
    protected $_handlers = [];


    /**
     * Setup components
     * @param array $handlers
     */
    public function __construct(array $handlers)
    {
        $this->_handlers = $handlers;
    }


    /**
     * Main process
     * @param string $query
     * @param bool $service
     * @throws \RuntimeException
     * @throws \Exception
     * @return mixed
     */
    public function handle($query, $service = false)
    {
        // init context
        $context = new Context();
        $context->query = (string)$query;
        $context->service = (bool)$service;

        // start event
        $this->fire('start', ['context' => &$context]);

        // chain handlers
        foreach($this->_handlers as $name => $handler) {
            if($handler instanceof Handler) {

                // run
                try {
                    $this->fire($name . '.start', ['context' => &$context]);
                    $context = $handler->handle($context);
                }
                catch(\Exception $e) {

                    // code as event
                    if($e->getCode() > 0) {
                        $this->fire($e->getCode(), ['context' => &$context]);
                    }
                    // forward exception
                    else {
                        throw $e;
                    }

                }

                // must return Context
                if(!($context instanceof Context)) {
                    throw new \RuntimeException('Handler "' . $name . '" must return the Context object.');
                }

                // next
                $this->fire($name . '.end', ['context' => &$context]);

            }
        }

        // end process
        $this->fire('end');

        return $context->data;
    }


    /**
     * Main process as a Service
     * @param $query
     * @return mixed
     */
    public function __invoke($query)
    {
        return $this->handle($query, true);
    }

}