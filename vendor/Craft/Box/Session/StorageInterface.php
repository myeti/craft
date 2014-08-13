<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Session;

use Craft\Data\ProviderInterface;

interface StorageInterface extends ProviderInterface
{

    /**
     * Save data in session
     */
    public function save();

} 