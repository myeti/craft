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

interface Storage
{

    /**
     * Move to another repository
     * @param string $path
     * @return $this
     */
    public function in($path);

    /**
     * List items in current repository
     * @return array
     */
    public function all();

    /**
     * Check if repository|item exists
     * @param string $path
     * @return bool
     */
    public function has($path);

    /**
     * Get remote item into local
     * @param string $path
     * @param string $local
     * @return mixed
     */
    public function get($path, $local);

    /**
     * Set remote item from local
     * @param string $path
     * @param string $local
     * @return bool
     */
    public function set($path, $local);

    /**
     * Create repository|item
     * @param string $path
     * @return bool
     */
    public function create($path);

    /**
     * Rename repository|item
     * @param string $path
     * @param string $to
     * @return bool
     */
    public function rename($path, $to);

    /**
     * Delete repository|item
     * @param string $path
     * @return bool
     */
    public function delete($path);

} 