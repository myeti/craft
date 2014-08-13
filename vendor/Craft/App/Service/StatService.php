<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Service;

use Craft\App\Service;
use Craft\App\Request;
use Craft\App\Response;
use Craft\Log\Logger;
use Craft\Box\Session;

/**
 * Keep elapsed time in memory
 */
class StatService extends Service
{

    /**
     * End of execution
     * @param Request $request
     * @param Response $response
     */
    public function finish(Request $request, Response $response)
    {
        // get data
        $elapsed = microtime(true) - $request->start;
        $average = Session::get('craft.time.average');
        $i = Session::get('craft.time.i');

        // update
        $average = (($average * $i) + $elapsed) / ++$i;
        Session::set('craft.time.average', $average);
        Session::set('craft.time.i', $i);

        Logger::info('App.Statistics : execution time ' . number_format($elapsed, 4) . 's');
        Logger::info('App.Statistics : average execution time ' . number_format($average, 4) . 's (i:' . $i . ')');
    }

}