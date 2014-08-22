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

use Craft\App;
use Craft\Trace\Logger;
use Craft\Box\Session;

/**
 * Keep elapsed time in memory
 */
class StatService extends App\Service
{

    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.end' => 'onKernelEnd'];
    }


    /**
     * End of execution
     * @param App\Request $request
     * @param App\Response $response
     */
    public function onKernelEnd(App\Request $request, App\Response $response)
    {
        // get data
        $elapsed = microtime(true) - $request->start;
        $average = Session::get('craft.time.average');
        $i = Session::get('craft.time.i');

        // update
        $average = (($average * $i) + $elapsed) / ++$i;
        Session::set('craft.time.average', $average);
        Session::set('craft.time.i', $i);

        Logger::info('Execution time ' . number_format($elapsed, 4) . 's');
        Logger::info('Average execution time ' . number_format($average, 4) . 's (i:' . $i . ')');
    }

}