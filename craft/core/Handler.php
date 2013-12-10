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

use craft\core\data\Context;

interface Handler
{

    /**
     * Handle an give back the context
     * @param Context $context
     * @return Context
     */
    public function handle(Context $context);

}