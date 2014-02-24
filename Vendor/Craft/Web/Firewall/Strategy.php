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

use Craft\Web\Request;

interface Strategy
{

    /**
     * Apply strategy on request
     * @param Request $request
     * @return bool
     */
    public function pass(Request $request);

} 