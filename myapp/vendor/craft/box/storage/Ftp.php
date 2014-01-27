<?php

namespace craft\box\storage;

class Ftp
{

    /** @var resource */
    protected $_remote;


    /**
     * Open a FTP remote connection
     * @param $url
     * @param bool $secure
     */
    public function __construct($url, $secure = false)
    {
        $this->_remote = $secure ? ftp_ssl_connect($url) : ftp_connect($url);
    }


    /**
     * Log in ftp
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password)
    {
        return ftp_login($this->_remote, $username, $password);
    }


    /**
     * Get current directory
     * @return string
     */
    public function current()
    {
        return ftp_pwd($this->_remote);
    }


    /**
     * Get current directory
     * @param string $of
     * @return string
     */
    public function listing($of = '.')
    {
        return ftp_nlist($this->_remote, $of);
    }


    /**
     * Move to another directory
     * @param $to
     * @return bool
     */
    public function walk($to)
    {
        $moved = true;
        $dirs = explode('/', trim($to, '/'));
        foreach($dirs as $dir) {
            $moved &= ftp_chdir($this->_remote, $dir);
        }

        return $moved;
    }


    /**
     * Delete file or directory
     * @param $file
     * @return bool
     */
    public function delete($file)
    {
        return ftp_delete($this->_remote, $file) ?: ftp_rmdir($this->_remote, $file);
    }


    /**
     * Create directory
     * @param $directory
     * @param int $mode
     * @internal param int $mod
     * @return bool
     */
    public function create($directory, $mode = null)
    {
        $created = ftp_mkdir($this->_remote, $directory);
        if($mode) {
            ftp_chmod($this->_remote, $mode, $directory);
        }

        return $created;
    }


    /**
     * Rename file or directory
     * @param $old
     * @param $new
     * @return bool
     */
    public function rename($old, $new)
    {
        return ftp_rename($this->_remote, $old, $new);
    }


    /**
     * Move file or directory
     * @param $what
     * @param $to
     * @return bool
     */
    public function move($what, $to)
    {
        $filename = pathinfo($what, PATHINFO_BASENAME);
        $to .= '/' . $filename;
        return ftp_rename($this->_remote, $filename, $to);
    }


    /**
     * Download file
     * @param $filename
     * @param $to
     * @return $this
     */
    public function download($filename, $to)
    {
        return ftp_get($this->_remote, $to, $filename, FTP_BINARY);
    }


    /**
     * Upload file to ftp
     * @param $filename
     * @param null $to
     * @return $this
     */
    public function upload($filename, $to = null)
    {
        return ftp_put($this->_remote, $to, $filename, FTP_BINARY);
    }


    /**
     * Close connection
     */
    public function __destruct()
    {
        ftp_close($this->_remote);
    }

} 