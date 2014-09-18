<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Io;

class FTP implements Storage, Resource
{

    /** @var resource */
    protected $remote;


    /**
     * Open connection
     * @param string $url
     * @param string $username
     * @param string $password
     * @param bool $secure
     */
    public function __construct($url, $username, $password, $secure = false)
    {
        $this->open($url, $username, $password, $secure);
    }


    /**
     * Open resource
     * @param mixed $url
     * @param string $username
     * @param string $password
     * @param bool $secure
     * @return bool
     */
    public function open($url, $username = null, $password = null, $secure = false)
    {
        if($this->remote) {
            $this->close();
        }

        $this->remote = $secure ? ftp_ssl_connect($url) : ftp_connect($url);
        if($username and $password) {
            ftp_login($this->remote, $username, $password);
        }
    }


    /**
     * List elements in path
     * @param string $path
     * @return array
     */
    public function all($path = '.')
    {
        return ftp_nlist($this->remote, $path);
    }


    /**
     * Check if file exists
     * @param string $filename
     * @return bool
     */
    public function has($filename)
    {
        $list = ftp_nlist($this->remote, dirname($filename));
        return in_array($filename, $list);
    }


    /**
     * Move to another repository
     * @param string $path
     * @return $this
     */
    public function in($path)
    {
        ftp_chdir ($this->remote, $path);
        return $this;
    }


    /**
     * Download filename
     * @param string $filename
     * @param string $local
     * @return bool
     */
    public function get($filename, $local)
    {
        return ftp_get($this->remote, $local, $filename, FTP_BINARY);
    }


    /**
     * Read data from resource
     * @param string $filename
     * @param int $mode
     * @return string
     */
    public function read($filename = null, $mode = FTP_BINARY)
    {
        $tmp = tmpfile();
        ftp_fget($this->remote, $tmp, $filename, $mode);
        fseek($tmp, 0);
        $content = stream_get_contents($tmp);
        fclose($tmp);
        return $content;
    }


    /**
     * Upload filename
     * @param string $filename
     * @param string $local
     * @param int $mode
     * @return bool
     */
    public function set($filename, $local, $mode = FTP_BINARY)
    {
        return ftp_put($this->remote, $filename, $local, $mode);
    }


    /**
     * Write data on resource
     * @param string $filename
     * @param string $content
     * @param int $mode
     * @return bool
     */
    public function write($filename, $content = '', $mode = FTP_BINARY)
    {
        $tmp = tmpfile();
        fwrite($tmp, $content);
        fseek($tmp, 0);
        $written = ftp_fput($this->remote, $filename, $tmp, $mode);
        fclose($tmp);
        return $written;
    }


    /**
     * Create directory
     * @param string $path
     * @param int $mode
     * @return bool
     */
    public function create($path, $mode = null)
    {
        $bool = ftp_mkdir($this->remote, $path);
        if($mode) {
            ftp_chmod($this->remote, $mode, $path);
        }

        return $bool;
    }


    /**
     * Rename file or directory
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function rename($old, $new)
    {
        return ftp_rename($this->remote, $old, $new);
    }


    /**
     * Delete path
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        return ftp_delete($this->remote, $path) ?: ftp_rmdir($this->remote, $path);
    }


    /**
     * Close resource
     * @return bool
     */
    public function close()
    {
        return ftp_close($this->remote);
    }


    /**
     * Close connection
     */
    public function __destruct()
    {
        $this->close();
    }

}