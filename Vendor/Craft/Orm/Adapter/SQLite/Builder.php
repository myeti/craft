<?php

namespace Craft\Orm\Adapter\SQLite;

use Craft\Orm\Native\Builder as NativeBuilder;

class Builder extends NativeBuilder
{

    /**
     * Fix <autoincrement> syntax
     */
    public function __construct()
    {
        $this->syntax['primary'] = 'primary key autoincrement';
    }

} 