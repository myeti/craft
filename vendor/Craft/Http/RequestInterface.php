<?php

namespace Craft\Http;

interface RequestInterface
{

    /**
     * Get code
     * @return int
     */
    public function code();

    /**
     * Get method
     * @return string
     */
    public function method();

    /**
     * Is secure
     * @return bool
     */
    public function secure();

    /**
     * Is async
     * @return bool
     */
    public function ajax();

    /**
     * Get url
     * @return string
     */
    public function url();

    /**
     * Get header
     * @return string
     */
    public function header($name);

    /**
     * Get headers
     * @return array
     */
    public function headers();

    /**
     * Get _server
     * @return string
     */
    public function server($name);

    /**
     * Get all _server
     * @return array
     */
    public function servers();

    /**
     * Get _env
     * @return string
     */
    public function env($name);

    /**
     * Get all _env
     * @return array
     */
    public function envs();

    /**
     * Get _get
     * @return string
     */
    public function param($name);

    /**
     * Get all _get
     * @return array
     */
    public function params();

    /**
     * Get _post
     * @return string
     */
    public function value($name);

    /**
     * Get all _post
     * @return array
     */
    public function values();

    /**
     * Get _file
     * @return string
     */
    public function file($name);

    /**
     * Get all _file
     * @return array
     */
    public function files();

    /**
     * Get _cookie
     * @return string
     */
    public function cookie($name);

    /**
     * Get all _cookie
     * @return array
     */
    public function cookies();

    /**
     * Get accept header
     * @return Request\Accept
     */
    public function accept();

    /**
     * Get cli data
     * @return Request\Cli
     */
    public function cli();

    /**
     * Set custom data
     * @return void
     */
    public function set($name, $value);

    /**
     * Get custom data
     * @return mixed
     */
    public function get($name);

    /**
     * Get user agent
     * @return string
     */
    public function agent();

    /**
     * Get user ip
     * @return string
     */
    public function ip();

    /**
     * Get user locale
     * @return string
     */
    public function locale();

    /**
     * Get time
     * @return float
     */
    public function time();

}