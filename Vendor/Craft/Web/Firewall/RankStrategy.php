<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Web\Firewall;

use Craft\Box\Auth;
use Craft\Web\Request;

class RankStrategy implements Strategy
{

    /**
     * Apply strategy on request
     * @param Request $request
     * @return bool
     */
    public function pass(Request $request)
    {
        if(isset($request->meta['auth']) and Auth::rank() < $request->meta['auth']) {
            return false;
        }

        return true;
    }

}