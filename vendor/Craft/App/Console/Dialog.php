<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Console;

class Dialog
{

    /** @var resource */
    protected $input;


    /**
     * Open IO dialog
     */
    public function __construct()
    {
        $this->input = fopen('php://stdin', 'r');
    }


    /**
     * Write message
     * @param string $message
     * @return Dialog
     */
    public function say(...$message)
    {
        echo implode(null, $message);
        return $this;
    }


    /**
     * Write new line
     * @return Dialog
     */
    public function ln()
    {
        echo "\n";
        return $this;
    }


    /**
     * Get user input
     * @return string
     */
    public function read()
    {
        return trim(fgets($this->input));
    }


    /**
     * Ask question and get user answer
     * @param $message
     * @return string
     */
    public function ask(...$message)
    {
        $this->say(...$message)->say(' ');
        return $this->read();
    }

}