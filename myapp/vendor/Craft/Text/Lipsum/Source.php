<?php

namespace Craft\Text\Lipsum;

interface Source
{

    /**
     * Generate random text
     * @param int $words
     * @param int $lines
     * @param int $texts
     * @return string
     */
    public function generate($words, $lines, $texts);

} 