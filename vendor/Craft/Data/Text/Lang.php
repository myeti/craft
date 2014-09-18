<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data\Text;

use Craft\Data\Text\Lang\Indexer;
use Craft\Data\Text\Lang\IndexerInterface;

abstract class Lang
{

    /**
     * Set directory
     * @param string $dir
     */
    public static function in($dir)
    {
        return static::instance(new Indexer($dir));
    }

    /**
     * Load locale
     * @param $locale
     */
    public static function locale($locale)
    {
        return static::instance()->locale($locale);
    }


    /**
     * Translate message
     * @param string $text
     * @param array $vars
     * @return string
     */
    public static function translate($text, array $vars = [])
    {
        return static::instance()->translate($text, $vars);
    }


    /**
     * Save current table
     */
    public static function save()
    {
        return static::instance()->save();
    }


    /**
     * Indexer instance
     * @param IndexerInterface $indexer
     * @throws \InvalidArgumentException
     * @return IndexerInterface
     */
    public static function instance(IndexerInterface $indexer = null)
    {
        static $instance;
        if($indexer) {
            $instance = $indexer;
        }
        if(!$instance) {
            throw new \InvalidArgumentException('You must define an Indexer class for the Lang tool.');
        }

        return $instance;
    }

}