<?php

namespace Craft\Box\Text\Markdown\Tag;

use Craft\Box\Text\Markdown\Tag;

class LinkTag extends Tag
{

    /**
     * Transform md to html tag
     * @param string $text
     * @return string
     */
    public function transform($text)
    {
        $text = preg_replace('/([^!])\[(.+)\]\((.+) "(.+)"\)/', '$1<a href="$3" title="$4">$2</a>', $text);
        return preg_replace('/([^!])\[(.+)\]\((.+)\)/', '$1<a href="$3">$2</a>', $text);
    }

}