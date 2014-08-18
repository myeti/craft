<?php

namespace Craft\Text\Lang;

interface IndexerInterface
{

    /**
     * Load locale
     * @param $locale
     */
    public function locale($locale);

    /**
     * Translate message
     * @param string $text
     * @param array $vars
     * @return string
     */
    public function translate($text, array $vars = []);

    /**
     * Save current table
     */
    public function save();

} 