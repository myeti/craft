<?php

namespace Craft\Http;

interface ResponseInterface
{

    /**
     * Get or Set code
     * @param null|int $code
     * @return int
     */
    public function code($code = null);

    /**
     * Set header
     * @param string $name
     * @param string $value
     * @param bool $replace
     * @return mixed
     */
    public function header($name, $value, $replace = false);

    /**
     * Set cookie
     * @param string $name
     * @param string $value
     * @param int $expires
     * @return mixed
     */
    public function cookie($name, $value, $expires = 0);

    /**
     * Set body
     * @param string $content
     * @return mixed
     */
    public function content($content);

    /**
     * Response already sent ?
     * @return bool
     */
    public function sent();

    /**
     * Send response
     * @return string
     */
    public function send();

} 