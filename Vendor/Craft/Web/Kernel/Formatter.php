<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Web\Kernel;

use Craft\View\EngineInterface;
use Craft\Web\Handler;
use Craft\Web\Request;
use Craft\Web\Response;

class Formatter implements Handler
{

    /** @var Handler */
    protected $handler;

    /** @var EngineInterface */
    protected $engine;


    /**
     * Setup firewall
     * @param Handler $handler
     * @param Engine $engine
     */
    public function __construct(Handler $handler, EngineInterface $engine)
    {
        $this->handler = $handler;
        $this->engine = $engine;
    }


    /**
     * Main process
     * @param Request $request
     * @throws \Exception
     * @return mixed
     */
    public function handle(Request $request)
    {
        // run handler
        list($request, $response) = $this->handler->handle($request);

        // render if asked
        if(!empty($request->meta['render'])) {

            // run engine
            $content = $this->engine->render($request->meta['render'], $response->data);

            // update response
            $response->content = $content;

            // send response
            echo $response;

        }

        return [$request, $response];
    }

} 