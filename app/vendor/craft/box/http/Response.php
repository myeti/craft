<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\core\kit\http;

class Response extends Http
{

    /** @var string */
    public $content;

    /**
     * Set http code
     * @param int $code
     * @return $this
     */
    public function code($code = 200)
    {
        $this->header('HTTP/1.0 ' . $code);
        return $this;
    }

    /**
     * Set content
     * @param $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * File as content
     * @param $type
     * @param null $filename
     * @return $this
     */
    public function file($type, $filename = null)
    {
        $this->header('Content-type: application/' . $type);

        if($filename) {
            $this->header('Content-Disposition: attachment; filename="' . $filename . '"');
        }

        return $this;
    }

    /**
     * Force download
     * @param $filename
     */
    public function download($filename)
    {
        $this->header("Content-Disposition: attachment; filename=" . urlencode($filename));
        $this->header("Content-Type: application/force-download");
        $this->header("Content-Type: application/octet-stream");
        $this->header("Content-Type: application/download");
        $this->header("Content-Description: File Transfer");
        $this->header("Content-Length: " . filesize($filename));
    }

    /**
     * Redirect to
     * @param $url
     * @return $this
     */
    public function redirect($url)
    {
        $this->header('Location: ' . $url);
        return $this;
    }

    /**
     * Send response
     * @return bool|mixed
     */
    public function send()
    {
        // error
        if(headers_sent()) {
            return false;
        }

        // apply headers
        foreach($this->headers as $header) {
            header($header);
        }

        // display content
        echo $this->content;

        // close
        return true;
    }

} 