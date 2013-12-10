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
use craft\meta\EventException;
use craft\core\data\Context;

class Dispatcher
{

    use Events;

    /** @var array */
    protected $_handlers = [];


    /**
     * Setup components
     * @param array $handlers
     * @throws \InvalidArgumentException
     */
    public function __construct(array $handlers)
    {
        foreach($handlers as $name => $handler) {

            // error
            if(!($handler instanceof Handler)) {
                throw new \InvalidArgumentException('Dispatcher must receive only Handler objects in its constructor.');
            }

            // assign
            $this->handler($name, $handler);

        }
    }

    /**
     * Set handler
     * @param $name
     * @param Handler $handler
     */
    public function handler($name, Handler $handler)
    {
        $this->_handlers[$name] = $handler;
    }


    /**
     * Main process
     * @param string $query
     * @param bool $service
     * @throws \RuntimeException
     * @throws \Exception
     * @return mixed
     */
    public function query($query, $service = false)
    {
        // init context
        $context = new Context();
        $context->query = (string)$query;
        $context->service = (bool)$service;

        // start event
        $this->fire('start', ['context' => &$context]);

        // chain handlers
        foreach($this->_handlers as $name => $handler) {

            // run
            try {
                $this->fire($name . '.start', ['context' => &$context]);
                $context = $handler->handle($context);
            }
            catch(EventException $e) {

                // fire inner event
                $this->fire($e->name, array_merge(['context' => &$context], $e->args));
                break;

            }
            catch(\Exception $e) {

                // forward exception
                throw $e;
                break;

            }

            // must return Context
            if(!($context instanceof Context)) {
                throw new \RuntimeException('Handler "' . $name . '" must return the Context object.');
            }

            // next
            $this->fire($name . '.end', ['context' => &$context]);
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
        return $this->query($query, true);
    }

}