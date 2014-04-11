<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Storage;

class Ftp implements Adapter
{

    /** @var resource */
    protected $remote;


    /**
     * Open a FTP remote connection
     * @param string $url
     * @param string $username
     * @param string $password
     * @param bool $secure
     */
    public function __construct($url, $username, $password, $secure = false)
    {
        $this->remote = $secure ? ftp_ssl_connect($url) : ftp_connect($url);
        ftp_login($this->remote, $username, $password);
    }


    /**
     * Get current directory
     * @param string $path
     * @return array
     */
    public function listing($path = '.')
    {
        return ftp_nlist($this->remote, $path);
    }


    /**
     * Check if path exists
     * @param string $path
     * @return bool
     */
    public function has($path)
    {
        $list = ftp_nlist($this->remote, dirname($path));
        return in_array($path, $list);
    }


    /**
     * Read file
     * @param string $filename
     * @return $this
     */
    public function read($filename)
    {
        $tmp = tmpfile();
        ftp_fget($this->remote, $tmp, $filename, FTP_BINARY);
        $content = stream_get_contents($tmp, -1, 0);
        fclose($tmp);
        return $content;
    }


    /**
     * Upload file to ftp
     * @param string $filename
     * @param string $content
     * @param int $where
     * @return $this
     */
    public function write($filename, $content, $where = self::REPLACE)
    {
        $tmp = tmpfile();

        if($where = self::BEFORE) {
            $content .= $this->read($filename);
        }
        elseif($where = self::AFTER) {
            $content = $this->read($filename) . $content;
        }

        fwrite($tmp, $content);
        $bool = ftp_fput($this->remote, $filename, $tmp, FTP_BINARY);
        fclose($tmp);
        return $bool;
    }


    /**
     * Create directory
     * @param string $path
     * @param int $mode
     * @return bool
     */
    public function create($path, $mode = 0755)
    {
        $created = ftp_mkdir($this->remote, $path);
        if($mode) {
            ftp_chmod($this->remote, $mode, $path);
        }

        return $created;
    }


    /**
     * Delete file or directory
     * @param string $filename
     * @return bool
     */
    public function delete($filename)
    {
        return ftp_delete($this->remote, $filename) ?: ftp_rmdir($this->remote, $filename);
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
     * Close connection
     */
    public function __destruct()
    {
        ftp_close($this->remote);
    }

}