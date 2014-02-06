<?php

namespace Craft\Text\Lipsum;

abstract class SourceDict implements Source
{

    /** @var array */
    protected $dict = [];

    /**
     * Generate random text
     * @param int $words
     * @param int $lines
     * @param int $texts
     * @return string
     */
    public function generate($words, $lines, $texts)
    {
        $output = '';

        for($i = 1; $i <= $texts; $i++) {
            for($j = 1; $j <= $lines; $j++) {
                $line = implode(' ', array_rand($this->dict, $words));
                $output .= ucfirst($line) . '. ';
            }
            $output .= "\n";
        }

        return rtrim($output, "\n");
    }

}