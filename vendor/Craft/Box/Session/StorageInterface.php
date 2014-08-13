<?php

namespace Craft\Box\Session;

use Craft\Data\ProviderInterface;

interface StorageInterface extends ProviderInterface
{

    /**
     * Save data in session
     */
    public function save();

} 