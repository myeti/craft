<?php

namespace Craft\Box\Text\Markdown\Tag;

use Craft\Box\Text\Markdown\Tag;

class QuoteTag implements Tag
{

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        $text = preg_replace_callback('/(\n>.*)+/', function($matches){

            // split
            $tab = preg_split('/\n/', $matches[0], -1, PREG_SPLIT_NO_EMPTY);

            // parse quote
            foreach($tab as $key => $line) {
                $tab[$key] = trim(preg_replace('/>(.*)/', "$1", $line));
            }

            // re process
            $quote = "<blockquote>\n\n" . implode("\n\n", $tab) . "\n<blockquote>";
//            $quote = $this->transform($quote);

            return "\n" . $quote;

        }, $text);

        return $text;
    }

}