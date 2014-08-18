<?php

namespace Craft\Text\Lang;

use Craft\Text\String;

class Indexer implements IndexerInterface
{

    /** @var string */
    protected $dir;

    /** @var string */
    protected $locale;

    /** @var array */
    protected $table = [];

    /** @var bool */
    protected $modified = false;


    /**
     * Set translation table dir
     * @param string $dir
     * @param null $locale
     * @throws \InvalidArgumentException
     */
    public function __construct($dir, $locale = null)
    {
        // set dir
        $this->dir = rtrim($dir, '/') . '/';
        if(!is_dir($this->dir)) {
            throw new \InvalidArgumentException('"' . $this->dir . '" is not a valid directory.');
        }

        // set locale
        $this->locale($locale ?: \Locale::getDefault());
    }


    /**
     * Load locale
     * @param $locale
     */
    public function locale($locale)
    {
        // save current locale
        if($this->locale and $this->modified) {
            $this->save();
        }

        // set new locale
        $this->locale = $locale;

        // load table
        $table = $this->dir . $locale . '.php';
        if(file_exists($table)) {
            $this->table = require $table;
        }
    }


    /**
     * Translate message
     * @param  string $text
     * @param  array $vars
     * @return string
     */
    public function translate($text, array $vars = [])
    {
        // clean
        $text = trim($text);

        // get table text
        $md5 = md5($text);
        if(!empty($this->table[$md5])) {
            $text = $this->table[$md5];
        }
        else {
            $this->table[$md5] = null;
            $this->modified = true;
        }

        // compile string
        $text = String::compose($text, $vars);

        return $text;
    }


    /**
     * Save current table
     */
    public function save()
    {
        // fill file
        $table = $this->dir . $this->locale . '.php';
        $content = print_r($this->table, true);
        $done = file_put_contents($table, $content);

        // set saved
        $this->modified = false;

        return $done;
    }


    /**
     * Save & close
     */
    public function __destruct()
    {
        if($this->locale and $this->modified) {
            $this->save();
        }
    }

} 