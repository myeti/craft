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
use Craft\Trace\Logger;

class Redirect extends Response
{

    /** @var string */
    protected $url;


    /**
     * New redirect response
     * @param string $url
     * @param bool $outside
     * @param int $code
     * @param array $headers
     */
    public function __construct($url, $outside = false, $code = 200, array $headers = [])
    {
        $this->url = $outside ? $url : url($url);
        $this->code = $code;
        $this->headers = $headers;
        $this->header('Location', $this->url);
    }


    /**
     * Stop execution
     * when sending response
     */
    public function send()
    {
        parent::send();
        Logger::info('Halt process and redirect to ' . $this->url);
        exit;
    }

} 