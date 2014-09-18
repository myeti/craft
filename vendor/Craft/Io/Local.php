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

class Local implements Storage
{

    /** @var string */
    protected $root;

    /** @var array */
    protected $cache = [];


    /**
     * Set root path
     * @param string $root
     */
    public function __construct($root)
    {
        $root = str_replace('/', DIRECTORY_SEPARATOR, $root);
        $root = str_replace('\\', DIRECTORY_SEPARATOR, $root);
        $root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->root = $root;
    }


    /**
     * List files and dirs
     * @param string $path
     * @param int $flags
     * @return array
     */
    public function all($path = null, $flags = null)
    {
        $path = $this->make($path);
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*';
        return glob($path, $flags);
    }


    /**
     * Move to another repository
     * @param string $path
     * @return $this
     */
    public function in($path)
    {
        $this->root .= rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        return $this;
    }


    /**
     * Check if path exists
     * @param string $path
     * @return bool
     */
    public function has($path)
    {
        $path = $this->make($path);
        return file_exists($path);
    }


    /**
     * Get file
     * @param string $filename
     * @param string $mode
     * @return mixed
     */
    public function get($filename, $mode = 'a+')
    {
        $filename = $this->make($filename);
        return new File($filename, $mode);
    }


    /**
     * Read file content
     * @param string $filename
     * @return string
     */
    public function read($filename)
    {
        $filename = $this->make($filename);
        return file_get_contents($filename);
    }


    /**
     * Copy file
     * @param string $filename
     * @param string $from
     * @return bool
     */
    public function set($filename, $from)
    {
        $filename = $this->make($filename);
        $from = $this->make($from);
        return copy($from, $filename);
    }


    /**
     * Write content into file, create if not exists
     * @param string $filename
     * @param string $content
     * @return bool
     */
    public function write($filename, $content)
    {
        $path = $this->make($filename);
        $base = dirname($path);

        // create dir
        if(!file_exists($base)) {
            mkdir($base, 0755, true);
        }

        return file_put_contents($path, $content);
    }


    /**
     * Create path
     * @param string $path
     * @param int $chmod
     * @return bool
     */
    public function create($path, $chmod = 0755)
    {
        $path = $this->make($path);
        return mkdir($path, $chmod, true);
    }


    /**
     * Delete path
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        $path = $this->make($path);
        return is_dir($path) ? rmdir($path) : unlink($path);
    }


    /**
     * Rename path
     * @param string $old
     * @param string $new
     * @return bool
     */
    public function rename($old, $new)
    {
        $old = $this->make($old);
        $new = $this->make($new);
        return rename($old, $new);
    }


    /**
     * Make full path
     * @param string $path
     * @return string
     */
    protected function make($path)
    {
        // already made
        if(isset($this->cache[$path])) {
            return $this->cache[$path];
        }

        // clean path
        $full = str_replace('/', DIRECTORY_SEPARATOR, $path);
        $full = str_replace('\\', DIRECTORY_SEPARATOR, $full);
        $full = $this->root . ltrim($full, DIRECTORY_SEPARATOR);

        // add in cache
        $this->cache[$path] = $full;

        return $full;
    }

}