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
use Craft\View\Engine;

class View extends Response
{

    /**
     * New template response
     * @param string $filename
     * @param array $data
     * @param int $code
     * @param array $headers
     */
    public function __construct($filename, $data = [], $code = 200, array $headers = [])
    {
        $this->content = Engine::make($filename, $data);
        $this->code = $code;
        $this->headers = $headers;
    }

} 