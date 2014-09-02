<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Response;

use Craft\App\Response;

class TeaPot extends Response
{

    /**
     * New teapot response
     * @param string $content
     * @param array $headers
     */
    public function __construct($content = '', array $headers = [])
    {
        parent::__construct($content, 418, $headers);
    }

} 